<?php
namespace App\Models;

use CodeIgniter\Model;

class BillingModel extends Model
{
    protected $table            = 'billing';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $deletedField     = 'deleted_at';

    protected $allowedFields    = [
        'invoice_number', 'patient_id', 'appointment_id', 'branch_id',
        'bill_date', 'due_date',
        'subtotal', 'tax_amount', 'discount_amount', 'total_amount',
        'paid_amount', 'balance', 'payment_status', 'payment_method',
        'insurance_claim_number', 'notes', 'created_by',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'patient_id' => 'required|integer',
        'invoice_number' => 'required|max_length[50]|is_unique[billing.invoice_number,id,{id}]',
        'bill_date' => 'required|valid_date',
        'due_date' => 'permit_empty|valid_date',
        'subtotal' => 'required|decimal',
        'total_amount' => 'required|decimal',
        'payment_status' => 'permit_empty|in_list[pending,partial,paid,overdue,cancelled]',
        'branch_id' => 'required|integer',
    ];

    // Relationships
    public function getPatient()
    {
        return $this->db->table('patients')
            ->where('id', $this->patient_id ?? null)
            ->get()
            ->getRowArray();
    }

    public function getAppointment()
    {
        return $this->db->table('appointments')
            ->where('id', $this->appointment_id ?? null)
            ->get()
            ->getRowArray();
    }

    public function getBranch()
    {
        return $this->db->table('branches')
            ->where('id', $this->branch_id ?? null)
            ->get()
            ->getRowArray();
    }

    public function getBillingItems()
    {
        return $this->db->table('billing_items')
            ->where('billing_id', $this->id ?? null)
            ->get()
            ->getResultArray();
    }

    public function getPayments()
    {
        return $this->db->table('payments')
            ->where('billing_id', $this->id ?? null)
            ->orderBy('paid_at', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function getInsuranceClaim()
    {
        return $this->db->table('insurance_claims')
            ->where('billing_id', $this->id ?? null)
            ->get()
            ->getRowArray();
    }

    // Get billing with all related data
    public function getBillingWithRelations($billingId)
    {
        $billing = $this->find($billingId);
        if ($billing) {
            $billing['patient'] = $this->getPatient();
            $billing['appointment'] = $this->getAppointment();
            $billing['branch'] = $this->getBranch();
            $billing['items'] = $this->getBillingItems();
            $billing['payments'] = $this->getPayments();
            $billing['insurance_claim'] = $this->getInsuranceClaim();
            $billing['total_paid'] = array_sum(array_column($billing['payments'], 'amount'));
        }
        return $billing;
    }

    // Helper methods
    public function calculateBalance($billingId)
    {
        $billing = $this->find($billingId);
        if (!$billing) {
            return 0;
        }

        $totalPaid = $this->db->table('payments')
            ->where('billing_id', $billingId)
            ->selectSum('amount')
            ->get()
            ->getRowArray();

        $paidAmount = $totalPaid['amount'] ?? 0;
        $balance = $billing['total_amount'] - $paidAmount;

        // Update billing record
        $this->update($billingId, [
            'paid_amount' => $paidAmount,
            'balance' => $balance,
            'payment_status' => $balance <= 0 ? 'paid' : ($paidAmount > 0 ? 'partial' : 'pending')
        ]);

        return $balance;
    }

    public function getUnpaidBills($patientId = null, $branchId = null)
    {
        $builder = $this->where('payment_status !=', 'paid')
                       ->where('balance >', 0)
                       ->orderBy('due_date', 'ASC');
        
        if ($patientId) {
            $builder->where('patient_id', $patientId);
        }
        
        if ($branchId) {
            $builder->where('branch_id', $branchId);
        }
        
        return $builder->findAll();
    }

    /**
     * Get or create an active (unpaid) bill for a patient
     * This ensures all charges accumulate into one bill per patient
     */
    public function getOrCreateActiveBill($patientId, $branchId = 1, $createdBy = null)
    {
        // Validate patient ID
        if (empty($patientId)) {
            log_message('error', 'Cannot create bill: Patient ID is empty');
            throw new \InvalidArgumentException('Patient ID is required to create a bill.');
        }
        
        // Find active unpaid bill for this patient
        $activeBill = $this->where('patient_id', $patientId)
            ->where('payment_status !=', 'paid')
            ->where('payment_status !=', 'cancelled')
            ->orderBy('created_at', 'DESC')
            ->first();

        if ($activeBill) {
            return $activeBill;
        }

        // Get current user ID from session if not provided
        if (empty($createdBy)) {
            $createdBy = session()->get('user_id');
        }
        
        // If still no user ID, try to get first admin user as fallback
        if (empty($createdBy)) {
            $userModel = model('App\Models\UserModel');
            $adminUser = $userModel->where('role', 'admin')
                ->where('is_active', 1)
                ->first();
            $createdBy = $adminUser['id'] ?? null;
        }
        
        // If still no valid user, set to NULL (requires nullable foreign key or valid default)
        // For now, we'll throw an error if no user is found
        if (empty($createdBy)) {
            log_message('error', 'Cannot create bill: No valid user ID found for created_by field');
            throw new \RuntimeException('Cannot create bill: No valid user ID available. Please ensure you are logged in.');
        }

        // Create new active bill with unique invoice number
        // Note: invoice_number column is VARCHAR(20), so we must keep it short
        // Format: B{date}{patientId}{random} = B + 8 + 6 + 4 = 19 chars max
        $maxAttempts = 50;
        $attempt = 0;
        $inserted = false;
        $insertId = null;
        
        do {
            // Generate short invoice number that fits in 20 characters
            // Format: B{date}{patientId}{random}
            // B = 1, date (Ymd) = 8, patientId (padded to 6) = 6, random = 4 = 19 chars total
            $datePart = date('Ymd'); // 8 characters: 20251213
            $patientPart = str_pad((string)$patientId, 6, '0', STR_PAD_LEFT); // 6 characters: 000123
            $random = mt_rand(1000, 9999); // 4 characters: 1234
            $invoiceNumber = 'B' . $datePart . $patientPart . $random; // 19 characters total
            
            // Double-check length (should be 19, but safety check)
            if (strlen($invoiceNumber) > 20) {
                $invoiceNumber = substr($invoiceNumber, 0, 20);
            }
            
            // Check if this invoice number already exists using raw query for better performance
            $existing = $this->db->table('billing')
                ->where('invoice_number', $invoiceNumber)
                ->countAllResults(false) > 0;
            
            if (!$existing) {
                // Number is unique, try to insert
                $billData = [
                    'invoice_number' => $invoiceNumber,
                    'patient_id' => $patientId,
                    'branch_id' => $branchId,
                    'bill_date' => date('Y-m-d'),
                    'due_date' => date('Y-m-d', strtotime('+30 days')), // 30 days for consolidated bills
                    'subtotal' => 0.00,
                    'tax_amount' => 0.00,
                    'discount_amount' => 0.00,
                    'total_amount' => 0.00,
                    'paid_amount' => 0.00,
                    'balance' => 0.00,
                    'payment_status' => 'pending',
                    'created_by' => $createdBy,
                ];
                
                try {
                    $this->insert($billData);
                    $insertId = $this->getInsertID();
                    $inserted = true;
                } catch (\Exception $e) {
                    // If insert fails due to duplicate, continue loop
                    if (strpos($e->getMessage(), 'Duplicate entry') !== false || 
                        strpos($e->getMessage(), '1062') !== false ||
                        strpos($e->getMessage(), 'Duplicate') !== false) {
                        log_message('warning', 'Duplicate invoice number on insert attempt ' . ($attempt + 1) . ': ' . $invoiceNumber);
                        $inserted = false;
                        // Increase wait time slightly with each attempt
                        usleep(10000 + ($attempt * 1000)); // 10ms + incremental delay
                    } else {
                        // Different error, re-throw
                        log_message('error', 'Error inserting bill: ' . $e->getMessage());
                        throw $e;
                    }
                }
            } else {
                // Duplicate found in pre-check, wait and retry with new random
                usleep(10000 + ($attempt * 1000)); // 10ms + incremental delay
            }
            
            $attempt++;
        } while (!$inserted && $attempt < $maxAttempts);
        
        // If we still couldn't insert after max attempts, throw error
        if (!$inserted) {
            log_message('error', 'Failed to generate unique invoice number after ' . $maxAttempts . ' attempts. Last attempt: ' . ($invoiceNumber ?? 'N/A'));
            throw new \RuntimeException('Unable to generate unique invoice number after multiple attempts. Please try again.');
        }
        
        return $this->find($insertId);
    }

    /**
     * Recalculate bill totals from all items
     */
    public function recalculateBill($billingId)
    {
        $items = $this->db->table('billing_items')
            ->where('billing_id', $billingId)
            ->get()
            ->getResultArray();

        $subtotal = 0.00;
        foreach ($items as $item) {
            $subtotal += (float)($item['total_price'] ?? 0);
        }

        $billing = $this->find($billingId);
        if (!$billing) {
            return false;
        }

        $taxAmount = (float)($billing['tax_amount'] ?? 0);
        $discountAmount = (float)($billing['discount_amount'] ?? 0);
        $totalAmount = $subtotal + $taxAmount - $discountAmount;

        // Get total paid
        $totalPaid = $this->db->table('payments')
            ->where('billing_id', $billingId)
            ->selectSum('amount')
            ->get()
            ->getRowArray();
        $paidAmount = (float)($totalPaid['amount'] ?? 0);
        $balance = $totalAmount - $paidAmount;

        // Update payment status
        $paymentStatus = 'pending';
        if ($balance <= 0) {
            $paymentStatus = 'paid';
        } elseif ($paidAmount > 0) {
            $paymentStatus = 'partial';
        }

        $this->update($billingId, [
            'subtotal' => $subtotal,
            'total_amount' => $totalAmount,
            'paid_amount' => $paidAmount,
            'balance' => $balance,
            'payment_status' => $paymentStatus,
        ]);

        return true;
    }

    /**
     * Automatically update room charges for in-patients
     * This ensures daily room charges are added automatically
     */
    public function updateInpatientRoomCharges($patientId, $billingId = null)
    {
        $patientModel = model('App\Models\PatientModel');
        $roomModel = model('App\Models\RoomModel');
        $billingItemModel = model('App\Models\BillingItemModel');
        
        // Get patient details
        $patient = $patientModel->find($patientId);
        if (!$patient) {
            return false;
        }
        
        // Check if patient is an in-patient
        $isInpatient = ($patient['admission_type'] === 'admission' && !empty($patient['assigned_room_id']));
        if (!$isInpatient || empty($patient['admission_date'])) {
            return false; // Not an in-patient
        }
        
        // Get room details
        $room = $roomModel->find($patient['assigned_room_id']);
        if (!$room || empty($room['rate_per_day'])) {
            return false; // No room or no rate
        }
        
        // Get or create active bill if not provided
        if (!$billingId) {
            $activeBill = $this->getOrCreateActiveBill($patientId, $patient['branch_id'] ?? 1, session()->get('user_id'));
            $billingId = $activeBill['id'] ?? null;
        }
        
        if (!$billingId) {
            return false;
        }
        
        // Calculate days from admission to today
        $admissionDate = new \DateTime($patient['admission_date']);
        $today = new \DateTime();
        $today->setTime(0, 0, 0); // Set to start of day
        $admissionDate->setTime(0, 0, 0);
        
        $daysStayed = $admissionDate->diff($today)->days + 1; // +1 to include admission day
        
        if ($daysStayed <= 0) {
            return false; // No days to charge
        }
        
        // Get existing room charges for this billing
        $existingCharges = $billingItemModel
            ->where('billing_id', $billingId)
            ->where('item_type', 'room_charge')
            ->findAll();
        
        // Calculate total days already billed
        $daysAlreadyBilled = 0;
        foreach ($existingCharges as $charge) {
            $daysAlreadyBilled += (int)($charge['quantity'] ?? 0);
        }
        
        // Calculate days that need to be billed
        $daysToBill = $daysStayed - $daysAlreadyBilled;
        
        if ($daysToBill > 0) {
            // Add room charges for the new days
            $ratePerDay = (float)($room['rate_per_day'] ?? 0);
            $totalCharge = $daysToBill * $ratePerDay;
            
            $billingItemData = [
                'billing_id' => $billingId,
                'item_type' => 'room_charge',
                'item_name' => 'Room Charges - ' . ($room['room_number'] ?? 'Room'),
                'description' => 'Daily room charges: ' . $daysToBill . ' day(s) @ ₱' . number_format($ratePerDay, 2) . '/day (Days ' . ($daysAlreadyBilled + 1) . '-' . $daysStayed . ')',
                'quantity' => $daysToBill,
                'unit_price' => $ratePerDay,
                'total_price' => $totalCharge,
            ];
            
            $billingItemModel->insert($billingItemData);
            
            // Recalculate bill totals
            $this->recalculateBill($billingId);
            
            return true;
        }
        
        return false; // No new charges to add
    }

    /**
     * Get consolidated bill for a patient with all items
     */
    public function getConsolidatedBill($patientId, $branchId = 1, $createdBy = null)
    {
        // Get current user ID from session if not provided
        if (empty($createdBy)) {
            $createdBy = session()->get('user_id');
        }
        
        $bill = $this->getOrCreateActiveBill($patientId, $branchId, $createdBy);
        if ($bill) {
            // Automatically update room charges for in-patients
            $this->updateInpatientRoomCharges($patientId, $bill['id']);
            
            // Recalculate bill totals
            $this->recalculateBill($bill['id']);
            $bill = $this->getBillingWithRelations($bill['id']);
        }
        return $bill;
    }

    /**
     * Finalize/close a bill (marks it as ready for payment)
     */
    public function finalizeBill($billingId)
    {
        $this->recalculateBill($billingId);
        $billing = $this->find($billingId);
        
        if ($billing && $billing['payment_status'] === 'pending') {
            // Bill is finalized, ready for payment
            return true;
        }
        return false;
    }

    /**
     * Check if patient is inpatient (has room assignment and admission_type = 'admission')
     */
    public function isInpatient($patientId)
    {
        $patient = $this->db->table('patients')
            ->select('admission_type, assigned_room_id')
            ->where('id', $patientId)
            ->get()
            ->getRowArray();
        
        return $patient && 
               $patient['admission_type'] === 'admission' && 
               !empty($patient['assigned_room_id']);
    }
}
