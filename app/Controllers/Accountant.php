<?php
namespace App\Controllers;
use App\Libraries\AuditLogger;
use App\Models\BillingModel;
use App\Models\BillingItemModel;
use App\Models\PaymentModel;
use App\Models\PatientModel;

class Accountant extends BaseController
{
    public function dashboard()
    {
        helper('url');
        $invoiceModel = model('App\\Models\\InvoiceModel');
        $paymentModel = model('App\\Models\\PaymentModel');
        $appointmentModel = model('App\\Models\\AppointmentModel');
        $patientModel = model('App\\Models\\PatientModel');

        $today = date('Y-m-d');

        // KPIs
        $invoicesToday = $invoiceModel
            ->where('DATE(issued_at)', $today)
            ->countAllResults();

        $paymentsToday = (float) ($paymentModel
            ->selectSum('amount', 'total')
            ->where('DATE(paid_at)', $today)
            ->first()['total'] ?? 0);

        $appointmentsToday = $appointmentModel
            ->where('DATE(appointment_date)', $today)
            ->countAllResults();

        // Recent mixed transactions (merge last 5 invoices and last 5 payments)
        $recent = [];
        $recentInvoices = $invoiceModel->orderBy('issued_at', 'DESC')->findAll(5);
        foreach ($recentInvoices as $inv) {
            $recent[] = [
                'date' => $inv['issued_at'],
                'type' => 'Invoice',
                'patient' => $inv['patient_name'],
                'amount' => (float)$inv['amount'],
                'status' => ucfirst($inv['status'] ?? 'unpaid'),
            ];
        }
        $recentPayments = $paymentModel->orderBy('paid_at', 'DESC')->findAll(5);
        foreach ($recentPayments as $pay) {
            $recent[] = [
                'date' => $pay['paid_at'],
                'type' => 'Payment',
                'patient' => $pay['patient_name'],
                'amount' => (float)$pay['amount'],
                'status' => 'Posted',
            ];
        }
        // Get recent appointments
        $recentAppointments = $appointmentModel
            ->select('appointments.*, patients.first_name, patients.last_name, patients.patient_id')
            ->join('patients', 'patients.id = appointments.patient_id')
            ->orderBy('appointments.appointment_date', 'DESC')
            ->orderBy('appointments.appointment_time', 'DESC')
            ->findAll(5);

        // Sort by date desc
        usort($recent, fn($a,$b) => strcmp($b['date'], $a['date']));
        $recent = array_slice($recent, 0, 10);

        return view('Accountant/dashboard', [
            'invoicesToday' => (int)$invoicesToday,
            'paymentsToday' => $paymentsToday,
            'appointmentsToday' => (int)$appointmentsToday,
            'recent' => $recent,
            'recentAppointments' => $recentAppointments,
        ]);
    }

    public function invoices()
    {
        helper('url');
        $invoiceModel = model('App\\Models\\InvoiceModel');
        $patientModel = model('App\\Models\\PatientModel');
        
        $invoices = $invoiceModel
            ->orderBy('issued_at', 'DESC')
            ->findAll(50);
            
        // Fetch active patients for the dropdown
        $patients = $patientModel->getActivePatients();
        
        // Format patients for the dropdown
        $formattedPatients = array_map(function($patient) {
            return [
                'id' => $patient['id'],
                'name' => $patient['first_name'] . ' ' . $patient['last_name'],
                'mobile' => $patient['phone'] ?? 'N/A'
            ];
        }, $patients);
            
        return view('Accountant/invoices', [
            'invoices' => $invoices,
            'patients' => $formattedPatients
        ]);
    }

    public function payments()
    {
        helper('url');
        $paymentsModel = model('App\\Models\\PaymentModel');
        
        $payments = $paymentsModel
            ->orderBy('paid_at', 'DESC')
            ->findAll(50);
            
        return view('Accountant/payments', [
            'payments' => $payments
        ]);
    }

    public function pendingCharges()
    {
        helper('url');
        $billingModel = new BillingModel();
        $billingItemModel = new BillingItemModel();
        $patientModel = new PatientModel();
        
        // Get filter status from query parameter
        $statusFilter = $this->request->getGet('status') ?? 'all';
        
        // Build query
        $builder = $billingModel->builder();
        $builder->select('billing.*, patients.first_name, patients.last_name, patients.patient_id as patient_code')
                ->join('patients', 'patients.id = billing.patient_id', 'left')
                ->orderBy('billing.created_at', 'DESC');
        
        // Apply status filter
        if ($statusFilter !== 'all') {
            if ($statusFilter === 'approved') {
                // For approved, treat 'partial' and 'paid' as approved
                $builder->whereIn('billing.payment_status', ['partial', 'paid']);
            } else {
                $builder->where('billing.payment_status', $statusFilter);
            }
        }
        
        $charges = $builder->get()->getResultArray();
        
        // Get item counts and doctor info for each charge
        $chargesWithDetails = [];
        foreach ($charges as $charge) {
            // Count items
            $itemCount = $billingItemModel->where('billing_id', $charge['id'])->countAllResults();
            
            // Get doctor name if appointment_id exists
            $doctorName = 'N/A';
            if (!empty($charge['appointment_id'])) {
                $appointmentModel = model('App\\Models\\AppointmentModel');
                $appointment = $appointmentModel
                    ->select('users.username as doctor_name')
                    ->join('users', 'users.id = appointments.doctor_id', 'left')
                    ->where('appointments.id', $charge['appointment_id'])
                    ->first();
                if ($appointment && !empty($appointment['doctor_name'])) {
                    $doctorName = $appointment['doctor_name'];
                }
            }
            
            $chargesWithDetails[] = [
                'id' => $charge['id'],
                'charge_number' => $charge['invoice_number'] ?? 'CHG-' . date('Ymd', strtotime($charge['created_at'])) . '-' . str_pad($charge['id'], 3, '0', STR_PAD_LEFT),
                'patient_name' => trim(($charge['first_name'] ?? '') . ' ' . ($charge['last_name'] ?? '')),
                'patient_code' => $charge['patient_code'] ?? 'N/A',
                'doctor_name' => $doctorName,
                'item_count' => $itemCount,
                'total_amount' => (float) ($charge['total_amount'] ?? 0),
                'created_at' => $charge['created_at'],
                'payment_status' => $charge['payment_status'] ?? 'pending',
            ];
        }
        
        // Count by status for tabs
        $statusCounts = [
            'all' => $billingModel->countAllResults(),
            'pending' => $billingModel->where('payment_status', 'pending')->countAllResults(),
            'approved' => $billingModel->whereIn('payment_status', ['partial', 'paid'])->countAllResults(),
            'paid' => $billingModel->where('payment_status', 'paid')->countAllResults(),
            'cancelled' => $billingModel->where('payment_status', 'cancelled')->countAllResults(),
        ];
        
        return view('Accountant/pending_charges', [
            'charges' => $chargesWithDetails,
            'statusFilter' => $statusFilter,
            'statusCounts' => $statusCounts,
        ]);
    }

    public function billing()
    {
        helper('url');
        // Redirect to consolidated bills - the main patient billing interface
        return redirect()->to(site_url('accountant/consolidated-bills'));
    }

    public function medicationBilling()
    {
        helper('url');
        $billingItemModel = new BillingItemModel();
        $builder = $billingItemModel->builder();
        $builder->select('billing_items.*, billing.patient_id, billing.bill_date, billing.payment_status, patients.first_name, patients.last_name, patients.patient_id as patient_code')
                ->join('billing', 'billing.id = billing_items.billing_id', 'left')
                ->join('patients', 'patients.id = billing.patient_id', 'left')
                ->where('billing_items.item_type', 'medication')
                ->orderBy('billing.bill_date', 'DESC');
        $items = $builder->get()->getResultArray();

        return view('Accountant/medication_billing', [
            'items' => $items,
        ]);
    }

    public function dischargePatient($patientId)
    {
        helper(['url', 'form']);
        $patientModel = model('App\\Models\\PatientModel');
        $roomModel = model('App\\Models\\RoomModel');
        $invoiceModel = model('App\\Models\\InvoiceModel');
        
        $patientId = (int) $patientId;
        
        // Get patient details
        $patient = $patientModel->find($patientId);
        if (!$patient) {
            return redirect()->to(site_url('accountant/billing'))->with('error', 'Patient not found.');
        }
        
        if (!$patient['assigned_room_id']) {
            return redirect()->to(site_url('accountant/billing'))->with('error', 'Patient is not assigned to any room.');
        }
        
        // Get room details
        $room = $roomModel->find($patient['assigned_room_id']);
        if (!$room) {
            return redirect()->to(site_url('accountant/billing'))->with('error', 'Room not found.');
        }
        
        // Calculate room charges ONLY for INPATIENTS (admission_type = 'admission')
        $roomCharges = 0;
        $daysStayed = 0;
        $isInpatient = ($patient['admission_type'] === 'admission' && !empty($patient['assigned_room_id']));
        
        if ($isInpatient && !empty($patient['admission_date'])) {
            $admissionDate = new \DateTime($patient['admission_date']);
            $dischargeDate = new \DateTime();
            $daysStayed = $admissionDate->diff($dischargeDate)->days + 1; // include admission day
            $roomCharges = $daysStayed * (float) ($room['rate_per_day'] ?? 0);
        }
        
        // AUTOMATIC BILLING: Add room charges to INPATIENT's consolidated bill only
        if ($isInpatient && $roomCharges > 0) {
            $billingModel = new BillingModel();
            $billingItemModel = new BillingItemModel();
            
            // Get or create active bill for this patient
            $activeBill = $billingModel->getOrCreateActiveBill($patientId, 1, session('user_id'));
            $billingId = $activeBill['id'];
            
            // Check if room charges already added (avoid duplicate on discharge)
            $existingRoomCharge = $billingItemModel
                ->where('billing_id', $billingId)
                ->where('item_type', 'room_charge')
                ->where('item_name LIKE', '%Room Charges%')
                ->first();
            
            if (!$existingRoomCharge) {
                // Add room charge item to the bill
                $billingItemData = [
                    'billing_id' => $billingId,
                    'item_type' => 'room_charge',
                    'item_name' => 'Room Charges - ' . ($room['room_number'] ?? 'Room'),
                    'description' => 'Inpatient room stay: ' . $daysStayed . ' day(s) @ ₱' . number_format($room['rate_per_day'] ?? 0, 2) . '/day',
                    'quantity' => $daysStayed,
                    'unit_price' => (float) ($room['rate_per_day'] ?? 0),
                    'total_price' => $roomCharges,
                ];
                $billingItemModel->insert($billingItemData);
                
                // Recalculate bill totals
                $billingModel->recalculateBill($billingId);
            }
        }
        
        // Update room occupancy
        $newOccupancy = $room['current_occupancy'] - 1;
        $roomModel->update($room['id'], [
            'current_occupancy' => $newOccupancy,
            'status' => $newOccupancy <= 0 ? 'available' : 'occupied'
        ]);
        
        // Update patient - remove room assignment
        $patientModel->update($patientId, [
            'assigned_room_id' => null,
            'admission_date' => null,
            'admission_type' => null
        ]);
        
        return redirect()->to(site_url('accountant/billing'))->with('success', 'Patient discharged successfully. Room is now available.');
    }

    public function patientBilling($patientId = null)
    {
        if (!$patientId) {
            return redirect()->to('/accountant/billing')->with('error', 'Patient ID is required.');
        }
        
        $patientModel = model('App\\Models\\PatientModel');
        $roomModel = model('App\\Models\\RoomModel');
        $invoiceModel = model('App\\Models\\InvoiceModel');
        $paymentModel = model('App\\Models\\PaymentModel');
        
        $patient = $patientModel->find($patientId);
        
        if (!$patient) {
            return redirect()->to('/accountant/billing')->with('error', 'Patient not found.');
        }
        
        // Get room details if patient is assigned to a room
        $room = null;
        $roomCharges = 0;
        $daysStayed = 0;
        
        if ($patient['assigned_room_id']) {
            $room = $roomModel->find($patient['assigned_room_id']);
            
            if ($room && $patient['admission_date']) {
                // Calculate days stayed
                $admissionDate = new \DateTime($patient['admission_date']);
                $currentDate = new \DateTime();
                $daysStayed = $admissionDate->diff($currentDate)->days + 1; // +1 to include admission day
                $roomCharges = $daysStayed * $room['rate_per_day'];
            }
        }
        
        // Get existing invoices and payments for this patient
        $invoices = $invoiceModel->where('patient_name', $patient['first_name'] . ' ' . $patient['last_name'])->orderBy('issued_at', 'DESC')->findAll();
        
        // Get payments by patient_id (if available) or by billing_id linked to this patient
        $payments = [];
        if (!empty($patient['id'])) {
            // First try to get payments by patient_id
            $paymentsByPatientId = $paymentModel->where('patient_id', $patient['id'])->orderBy('paid_at', 'DESC')->findAll();
            
            // Also get payments linked through billing records for this patient
            $billingModel = model('App\\Models\\BillingModel');
            $patientBillings = $billingModel->where('patient_id', $patient['id'])->findAll();
            $billingIds = array_column($patientBillings, 'id');
            
            if (!empty($billingIds)) {
                $paymentsByBilling = $paymentModel->whereIn('billing_id', $billingIds)->orderBy('paid_at', 'DESC')->findAll();
                // Merge and remove duplicates
                $allPayments = array_merge($paymentsByPatientId, $paymentsByBilling);
                $uniquePayments = [];
                $seenIds = [];
                foreach ($allPayments as $payment) {
                    if (!in_array($payment['id'], $seenIds)) {
                        $uniquePayments[] = $payment;
                        $seenIds[] = $payment['id'];
                    }
                }
                $payments = $uniquePayments;
            } else {
                $payments = $paymentsByPatientId;
            }
            
            // Only include payments made on or after admission date (for room charges)
            if (!empty($patient['admission_date'])) {
                $admissionDate = $patient['admission_date'];
                $payments = array_filter($payments, function($payment) use ($admissionDate) {
                    $paidAt = $payment['paid_at'] ?? $payment['created_at'] ?? '';
                    return !empty($paidAt) && strtotime($paidAt) >= strtotime($admissionDate);
                });
            }
        }
        
        // Calculate totals
        $totalInvoices = 0;
        foreach ($invoices as $invoice) {
            $totalInvoices += (float) $invoice['amount'];
        }
        
        $totalPayments = 0;
        foreach ($payments as $payment) {
            $totalPayments += (float) $payment['amount'];
        }
        
        // Calculate total charges (room charges + invoices)
        $totalCharges = $roomCharges + $totalInvoices;
        $balanceDue = $totalCharges - $totalPayments;
        
        return view('Accountant/patient_billing', [
            'patient' => $patient,
            'room' => $room,
            'roomCharges' => $roomCharges,
            'daysStayed' => $daysStayed,
            'invoices' => $invoices,
            'payments' => $payments,
            'totalInvoices' => $totalInvoices,
            'totalPayments' => $totalPayments,
            'totalCharges' => $totalCharges,
            'balanceDue' => $balanceDue
        ]);
    }

    public function allPatientBills()
    {
        helper('url');
        $patientModel = model('App\\Models\\PatientModel');
        $roomModel = model('App\\Models\\RoomModel');
        $invoiceModel = model('App\\Models\\InvoiceModel');
        $paymentModel = model('App\\Models\\PaymentModel');
        
        // Get all patients with room assignments
        $patientsWithRooms = $patientModel
            ->where('assigned_room_id IS NOT NULL', null, false)
            ->where('assigned_room_id !=', null)
            ->findAll();
        
        $allBills = [];
        
        foreach ($patientsWithRooms as $patient) {
            // Get room details
            $room = null;
            $roomCharges = 0;
            $daysStayed = 0;
            
            if ($patient['assigned_room_id']) {
                $room = $roomModel->find($patient['assigned_room_id']);
                
                if ($room && $patient['admission_date']) {
                    $admissionDate = new \DateTime($patient['admission_date']);
                    $currentDate = new \DateTime();
                    $daysStayed = $admissionDate->diff($currentDate)->days + 1;
                    $roomCharges = $daysStayed * $room['rate_per_day'];
                }
            }
            
            // Get invoices and payments - use patient_id to avoid matching other patients with same name
            $invoices = $invoiceModel->where('patient_name', $patient['first_name'] . ' ' . $patient['last_name'])->findAll();
            
            // Get payments by patient_id (if available) or by billing_id linked to this patient
            $payments = [];
            if (!empty($patient['id'])) {
                // First try to get payments by patient_id
                $paymentsByPatientId = $paymentModel->where('patient_id', $patient['id'])->findAll();
                
                // Also get payments linked through billing records for this patient
                $billingModel = model('App\\Models\\BillingModel');
                $patientBillings = $billingModel->where('patient_id', $patient['id'])->findAll();
                $billingIds = array_column($patientBillings, 'id');
                
                if (!empty($billingIds)) {
                    $paymentsByBilling = $paymentModel->whereIn('billing_id', $billingIds)->findAll();
                    // Merge and remove duplicates
                    $allPayments = array_merge($paymentsByPatientId, $paymentsByBilling);
                    $uniquePayments = [];
                    $seenIds = [];
                    foreach ($allPayments as $payment) {
                        if (!in_array($payment['id'], $seenIds)) {
                            $uniquePayments[] = $payment;
                            $seenIds[] = $payment['id'];
                        }
                    }
                    $payments = $uniquePayments;
                } else {
                    $payments = $paymentsByPatientId;
                }
                
                // Only include payments made on or after admission date (for room charges)
                if (!empty($patient['admission_date'])) {
                    $admissionDate = $patient['admission_date'];
                    $payments = array_filter($payments, function($payment) use ($admissionDate) {
                        $paidAt = $payment['paid_at'] ?? $payment['created_at'] ?? '';
                        return !empty($paidAt) && strtotime($paidAt) >= strtotime($admissionDate);
                    });
                }
            }
            
            $totalInvoices = 0;
            foreach ($invoices as $invoice) {
                $totalInvoices += (float) $invoice['amount'];
            }
            
            $totalPayments = 0;
            foreach ($payments as $payment) {
                $totalPayments += (float) $payment['amount'];
            }
            
            $totalCharges = $roomCharges + $totalInvoices;
            $balanceDue = $totalCharges - $totalPayments;
            
            $allBills[] = [
                'patient' => $patient,
                'room' => $room,
                'roomCharges' => $roomCharges,
                'daysStayed' => $daysStayed,
                'totalInvoices' => $totalInvoices,
                'totalPayments' => $totalPayments,
                'totalCharges' => $totalCharges,
                'balanceDue' => $balanceDue
            ];
        }
        
        return view('Accountant/all_patient_bills', [
            'allBills' => $allBills
        ]);
    }

    public function reports()
    {
        helper('url');
        $invoiceModel = model('App\\Models\\InvoiceModel');
        $paymentModel = model('App\\Models\\PaymentModel');

        $now = new \DateTimeImmutable('now');
        $d30 = $now->modify('-30 days')->format('Y-m-d 00:00:00');
        $d60 = $now->modify('-60 days')->format('Y-m-d 00:00:00');
        $d30_only = $now->modify('-30 days')->format('Y-m-d');

        // Revenue last 30d
        $revenue30d = (float) ($paymentModel
            ->selectSum('amount', 'total')
            ->where('paid_at >=', $d30)
            ->first()['total'] ?? 0);

        // Load invoices and payments for calculations
        $invoices = $invoiceModel->orderBy('issued_at', 'DESC')->findAll(500);
        $payments = $paymentModel->orderBy('paid_at', 'DESC')->findAll(500);

        // AR > 60d and aging buckets from unpaid invoices
        $arOver60d = 0.0;
        $agingBuckets = [
            '0-30' => 0.0,
            '31-60' => 0.0,
            '61-90' => 0.0,
            '>90' => 0.0,
        ];
        foreach ($invoices as $inv) {
            $isPaid = strtolower((string)($inv['status'] ?? '')) === 'paid';
            if ($isPaid) { continue; }
            $days = (int) floor((strtotime($now->format('Y-m-d')) - strtotime((string)$inv['issued_at'])) / 86400);
            $amt = (float)($inv['amount'] ?? 0);
            if ($days <= 30) { $agingBuckets['0-30'] += $amt; }
            elseif ($days <= 60) { $agingBuckets['31-60'] += $amt; }
            elseif ($days <= 90) { $agingBuckets['61-90'] += $amt; }
            else { $agingBuckets['>90'] += $amt; }
            if ($days > 60) { $arOver60d += $amt; }
        }

        // Avg Days to Pay (match by invoice_no when available)
        $invByNo = [];
        foreach ($invoices as $inv) {
            if (!empty($inv['invoice_no'])) { $invByNo[$inv['invoice_no']] = $inv; }
        }
        $diffs = [];
        foreach ($payments as $p) {
            $no = (string)($p['invoice_no'] ?? '');
            if ($no !== '' && isset($invByNo[$no])) {
                $days = (int) floor((strtotime((string)$p['paid_at']) - strtotime((string)$invByNo[$no]['issued_at'])) / 86400);
                if ($days >= 0) { $diffs[] = $days; }
            }
        }
        $avgDaysToPay = !empty($diffs) ? round(array_sum($diffs) / max(1, count($diffs))) : 0;

        return view('Accountant/reports', [
            'revenue30d' => $revenue30d,
            'arOver60d' => $arOver60d,
            'avgDaysToPay' => $avgDaysToPay,
            'aging' => $agingBuckets,
        ]);
    }

    public function newInvoice()
{
    helper('url');
    $patientModel = model('App\\Models\\PatientModel');
    
    // Fetch active patients
    $patients = $patientModel->getActivePatients();
    
    // Format patients for the dropdown
    $formattedPatients = array_map(function($patient) {
        return [
            'id' => $patient['id'],
            'name' => $patient['first_name'] . ' ' . $patient['last_name'],
            'mobile' => $patient['phone'] ?? 'N/A'
        ];
    }, $patients);

    return view('Accountant/invoices', ['patients' => $formattedPatients]);
}

    public function newPayment()
    {
        helper('url');
        $userRole = session('role');
        $isAdmin = ($userRole === 'admin');
        
        $patientModel = model('App\\Models\\PatientModel');
        
        $patients = $patientModel
            ->select('id, patient_id, first_name, last_name')
            ->where('is_active', 1)
            ->orderBy('last_name', 'ASC')
            ->orderBy('first_name', 'ASC')
            ->findAll(200);
        
        return view('Accountant/payment_new', [
            'patients' => $patients,
            'isAdmin' => $isAdmin,
        ]);
    }

    public function storeInvoice()
    {
        helper(['url', 'form']);
        $model = new \App\Models\InvoiceModel();
        $patientModel = model('App\\Models\\PatientModel');

        $patientId = $this->request->getPost('patient_id');
        $patient = $patientModel->find($patientId);
        
        if (!$patient) {
            return redirect()->back()->with('error', 'Patient not found.')->withInput();
        }

        $data = [
            'invoice_no'   => trim((string)$this->request->getPost('invoice_no')),
            'patient_name' => $patient['first_name'] . ' ' . $patient['last_name'],
            'amount'       => (float)$this->request->getPost('amount'),
            'status'       => $this->request->getPost('status') ?: 'unpaid',
            'issued_at'    => $this->request->getPost('invoice_date') ?: date('Y-m-d'),
        ];

        if ($data['amount'] <= 0) {
            return redirect()->back()->with('error', 'Valid amount is required.')->withInput();
        }

        $model->insert($data);
        AuditLogger::log('invoice_create', 'invoice_no=' . ($data['invoice_no'] ?: 'n/a') . ' amount=' . $data['amount']);
        return redirect()->to(site_url('accountant/invoices'))->with('success', 'Invoice created successfully.');
    }

    public function editInvoice($id)
    {
        helper('url');
        $invoiceModel = model('App\\Models\\InvoiceModel');
        $patientModel = model('App\\Models\\PatientModel');
        
        $invoice = $invoiceModel->find($id);
        if (!$invoice) {
            return redirect()->to(site_url('accountant/invoices'))->with('error', 'Invoice not found.');
        }

        $patients = $patientModel->getActivePatients();
        $formattedPatients = array_map(function($patient) {
            return [
                'id' => $patient['id'],
                'name' => $patient['first_name'] . ' ' . $patient['last_name'],
                'mobile' => $patient['phone'] ?? 'N/A'
            ];
        }, $patients);

        return view('Accountant/invoice_edit', [
            'invoice' => $invoice,
            'patients' => $formattedPatients
        ]);
    }

    public function updateInvoice($id)
    {
        helper(['url', 'form']);
        $invoiceModel = model('App\\Models\\InvoiceModel');
        $patientModel = model('App\\Models\\PatientModel');

        $invoice = $invoiceModel->find($id);
        if (!$invoice) {
            return redirect()->to(site_url('accountant/invoices'))->with('error', 'Invoice not found.');
        }

        $patientId = $this->request->getPost('patient_id');
        $patient = $patientModel->find($patientId);
        
        if (!$patient) {
            return redirect()->back()->with('error', 'Patient not found.')->withInput();
        }

        $data = [
            'invoice_no'   => trim((string)$this->request->getPost('invoice_no')),
            'patient_name' => $patient['first_name'] . ' ' . $patient['last_name'],
            'amount'       => (float)$this->request->getPost('amount'),
            'status'       => $this->request->getPost('status') ?: 'unpaid',
            'issued_at'    => $this->request->getPost('invoice_date') ?: date('Y-m-d'),
        ];

        if ($data['amount'] <= 0) {
            return redirect()->back()->with('error', 'Valid amount is required.')->withInput();
        }

        $invoiceModel->update($id, $data);
        AuditLogger::log('invoice_update', 'invoice_id=' . $id . ' invoice_no=' . ($data['invoice_no'] ?: 'n/a') . ' amount=' . $data['amount']);
        return redirect()->to(site_url('accountant/invoices'))->with('success', 'Invoice updated successfully.');
    }

    public function deleteInvoice($id)
    {
        helper('url');
        $invoiceModel = model('App\\Models\\InvoiceModel');
        
        $invoice = $invoiceModel->find($id);
        if (!$invoice) {
            return redirect()->to(site_url('accountant/invoices'))->with('error', 'Invoice not found.');
        }

        // Check if invoice is paid
        if ($invoice['status'] === 'paid') {
            return redirect()->to(site_url('accountant/invoices'))->with('error', 'Cannot delete a paid invoice.');
        }

        $invoiceModel->delete($id);
        AuditLogger::log('invoice_delete', 'invoice_id=' . $id);
        return redirect()->to(site_url('accountant/invoices'))->with('success', 'Invoice deleted successfully.');
    }
    public function insurance()
    {
        helper('url');
        $claimsModel = model('App\\Models\\InsuranceClaimModel');
        $claims = $claimsModel->orderBy('submitted_at', 'DESC')->findAll(50);
        return view('Accountant/insurance', ['claims' => $claims]);
    }

    public function newClaim()
    {
        helper(['url']);
        return view('Accountant/claim_new');
    }

    public function storeClaim()
    {
        helper(['url']);
        $claimsModel = model('App\\Models\\InsuranceClaimModel');

        $data = [
            'claim_no' => $this->request->getPost('claim_no'),
            'invoice_no' => $this->request->getPost('invoice_no'),
            'patient_name' => $this->request->getPost('patient_name'),
            'provider' => $this->request->getPost('provider'),
            'policy_no' => $this->request->getPost('policy_no'),
            'amount_claimed' => (float)$this->request->getPost('amount_claimed'),
            'amount_approved' => (float)($this->request->getPost('amount_approved') ?: 0),
            'status' => $this->request->getPost('status') ?: 'submitted',
            'submitted_at' => $this->request->getPost('submitted_at') ?: date('Y-m-d H:i:s'),
        ];

        $claimsModel->insert($data);
        AuditLogger::log('claim_create', 'claim_no=' . ($data['claim_no'] ?: 'n/a') . ' invoice_no=' . ($data['invoice_no'] ?: 'n/a'));
        return redirect()->to(site_url('accountant/insurance'));
    }

    public function viewClaim($id)
    {
        helper('url');
        $claimsModel = model('App\\Models\\InsuranceClaimModel');
        $claim = $claimsModel->find($id);

        if (!$claim) {
            return redirect()->to(site_url('accountant/insurance'))->with('error', 'Claim not found.');
        }

        return view('Accountant/claim_view', ['claim' => $claim]);
    }

    public function statements()
    {
        helper('url');
        $model = new \App\Models\PaymentModel();
        $payments = $model->orderBy('paid_at', 'DESC')->findAll(100);
        return view('Accountant/statements', ['payments' => $payments]);
    }

    public function viewPayment($paymentId)
    {
        helper('url');
        $userRole = session('role');
        $isAdmin = ($userRole === 'admin');
        $redirectUrl = $isAdmin ? site_url('admin/payments') : site_url('accountant/payments');
        
        $paymentModel = model('App\\Models\\PaymentModel');
        
        $payment = $paymentModel->find($paymentId);
        
        if (!$payment) {
            return redirect()->to($redirectUrl)->with('error', 'Payment not found.');
        }
        
        return view('Accountant/payment_view', [
            'payment' => $payment,
            'isAdmin' => $isAdmin,
        ]);
    }

    public function storePayment()
    {
        helper(['url', 'form']);

        $userRole = session('role');
        $isAdmin = ($userRole === 'admin');
        $redirectUrl = $isAdmin ? site_url('admin/payments') : site_url('accountant/payments');

        $patientId = (int) $this->request->getPost('patient_id');
        $invoice = trim((string) $this->request->getPost('invoice_no'));
        $amount  = (float) $this->request->getPost('amount');
        $paymentDate = (string) $this->request->getPost('payment_date');

        if ($patientId <= 0 || $amount <= 0) {
            return redirect()->back()->with('error', 'Patient and valid amount are required.')->withInput();
        }

        // Get patient details
        $patientModel = model('App\\Models\\PatientModel');
        $patient = $patientModel->find($patientId);
        
        if (!$patient) {
            return redirect()->back()->with('error', 'Patient not found.')->withInput();
        }

        $model = new \App\Models\PaymentModel();
        $model->insert([
            'patient_name' => $patient['first_name'] . ' ' . $patient['last_name'],
            'invoice_no'   => $invoice !== '' ? $invoice : null,
            'amount'       => $amount,
            'paid_at'      => $paymentDate !== '' ? $paymentDate : date('Y-m-d H:i:s'),
        ]);

        AuditLogger::log('payment_record', 'patient=' . $patient['first_name'] . ' ' . $patient['last_name'] . ' amount=' . $amount . ' invoice_no=' . ($invoice ?: 'n/a'));
        return redirect()->to($redirectUrl)
            ->with('success', 'Payment recorded successfully.');
    }

    public function editPayment($id)
    {
        helper('url');
        $userRole = session('role');
        $isAdmin = ($userRole === 'admin');
        $redirectUrl = $isAdmin ? site_url('admin/payments') : site_url('accountant/payments');
        
        $paymentModel = model('App\\Models\\PaymentModel');
        $patientModel = model('App\\Models\\PatientModel');
        
        $payment = $paymentModel->find($id);
        if (!$payment) {
            return redirect()->to($redirectUrl)->with('error', 'Payment not found.');
        }

        $patients = $patientModel
            ->select('id, patient_id, first_name, last_name')
            ->where('is_active', 1)
            ->orderBy('last_name', 'ASC')
            ->orderBy('first_name', 'ASC')
            ->findAll(200);

        return view('Accountant/payment_edit', [
            'payment' => $payment,
            'patients' => $patients,
            'isAdmin' => $isAdmin,
        ]);
    }

    public function updatePayment($id)
    {
        helper(['url', 'form']);
        $userRole = session('role');
        $isAdmin = ($userRole === 'admin');
        $redirectUrl = $isAdmin ? site_url('admin/payments') : site_url('accountant/payments');
        
        $paymentModel = model('App\\Models\\PaymentModel');
        $patientModel = model('App\\Models\\PatientModel');

        $payment = $paymentModel->find($id);
        if (!$payment) {
            return redirect()->to($redirectUrl)->with('error', 'Payment not found.');
        }

        $patientId = (int) $this->request->getPost('patient_id');
        $invoice = trim((string) $this->request->getPost('invoice_no'));
        $amount  = (float) $this->request->getPost('amount');
        $paymentDate = (string) $this->request->getPost('payment_date');

        if ($patientId <= 0 || $amount <= 0) {
            return redirect()->back()->with('error', 'Patient and valid amount are required.')->withInput();
        }

        $patient = $patientModel->find($patientId);
        if (!$patient) {
            return redirect()->back()->with('error', 'Patient not found.')->withInput();
        }

        $data = [
            'patient_name' => $patient['first_name'] . ' ' . $patient['last_name'],
            'invoice_no'   => $invoice !== '' ? $invoice : null,
            'amount'       => $amount,
            'paid_at'      => $paymentDate !== '' ? $paymentDate : date('Y-m-d H:i:s'),
        ];

        $paymentModel->update($id, $data);
        AuditLogger::log('payment_update', 'payment_id=' . $id . ' amount=' . $amount);
        return redirect()->to($redirectUrl)->with('success', 'Payment updated successfully.');
    }

    public function deletePayment($id)
    {
        helper('url');
        $userRole = session('role');
        $isAdmin = ($userRole === 'admin');
        $redirectUrl = $isAdmin ? site_url('admin/payments') : site_url('accountant/payments');
        
        $paymentModel = model('App\\Models\\PaymentModel');
        
        $payment = $paymentModel->find($id);
        if (!$payment) {
            return redirect()->to($redirectUrl)->with('error', 'Payment not found.');
        }

        $paymentModel->delete($id);
        AuditLogger::log('payment_delete', 'payment_id=' . $id);
        return redirect()->to($redirectUrl)->with('success', 'Payment deleted successfully.');
    }

    public function exportStatement()
    {
        // Simple CSV export of all payments
        $model = new \App\Models\PaymentModel();
        $payments = $model->orderBy('paid_at', 'DESC')->findAll();

        $filename = 'statement_' . date('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $fp = fopen('php://temp', 'w');
        fputcsv($fp, ['Date', 'Patient', 'Invoice', 'Amount']);
        foreach ($payments as $p) {
            fputcsv($fp, [
                $p['paid_at'],
                $p['patient_name'],
                $p['invoice_no'] ?? '',
                number_format((float)$p['amount'], 2),
            ]);
        }
        rewind($fp);
        $csv = stream_get_contents($fp);
        fclose($fp);

        AuditLogger::log('statement_export_csv', 'payments=' . count($payments));
        return service('response')->setStatusCode(200)->setHeader('Content-Type', $headers['Content-Type'])
            ->setHeader('Content-Disposition', $headers['Content-Disposition'])->setBody($csv);
    }

    // Export all invoices as CSV
    public function exportInvoicesCsv()
    {
        $model = new \App\Models\InvoiceModel();
        $invoices = $model->orderBy('issued_at', 'DESC')->findAll();

        $filename = 'invoices_' . date('Ymd_His') . '.csv';
        $fp = fopen('php://temp', 'w');
        fputcsv($fp, ['Issued At', 'Invoice No', 'Patient', 'Amount', 'Status']);
        foreach ($invoices as $i) {
            fputcsv($fp, [
                $i['issued_at'],
                $i['invoice_no'] ?? '',
                $i['patient_name'] ?? '',
                number_format((float)($i['amount'] ?? 0), 2),
                $i['status'] ?? '',
            ]);
        }
        rewind($fp); $csv = stream_get_contents($fp); fclose($fp);
        AuditLogger::log('invoices_export_csv', 'invoices=' . count($invoices));
        return service('response')
            ->setHeader('Content-Type', 'text/csv')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setBody($csv);
    }

    // Export ZIP: invoices.csv, payments.csv, aging.csv
    public function exportZip()
    {
        $invoiceModel = new \App\Models\InvoiceModel();
        $paymentModel = new \App\Models\PaymentModel();

        $invoices = $invoiceModel->orderBy('issued_at', 'DESC')->findAll(10000);
        $payments = $paymentModel->orderBy('paid_at', 'DESC')->findAll(10000);

        // Compute aging similar to reports()
        $now = new \DateTimeImmutable('now');
        $aging = ['0-30'=>0.0,'31-60'=>0.0,'61-90'=>0.0,'>90'=>0.0];
        foreach ($invoices as $inv) {
            $isPaid = strtolower((string)($inv['status'] ?? '')) === 'paid';
            if ($isPaid) continue;
            $days = (int) floor((strtotime($now->format('Y-m-d')) - strtotime((string)($inv['issued_at'] ?? ''))) / 86400);
            $amt = (float)($inv['amount'] ?? 0);
            if ($days <= 30) { $aging['0-30'] += $amt; }
            elseif ($days <= 60) { $aging['31-60'] += $amt; }
            elseif ($days <= 90) { $aging['61-90'] += $amt; }
            else { $aging['>90'] += $amt; }
        }

        if (class_exists('ZipArchive')) {
            $zip = new \ZipArchive();
            $tmp = tempnam(sys_get_temp_dir(), 'acct_zip_');
            $zip->open($tmp, \ZipArchive::OVERWRITE);

            // invoices.csv
            $fp = fopen('php://temp', 'w');
            fputcsv($fp, ['Issued At','Invoice No','Patient','Amount','Status']);
            foreach ($invoices as $i) {
                fputcsv($fp, [$i['issued_at'], $i['invoice_no'] ?? '', $i['patient_name'] ?? '', (float)($i['amount'] ?? 0), $i['status'] ?? '']);
            }
            rewind($fp); $csv = stream_get_contents($fp); fclose($fp);
            $zip->addFromString('invoices.csv', $csv);

            // payments.csv
            $fp = fopen('php://temp', 'w');
            fputcsv($fp, ['Paid At','Patient','Invoice','Amount']);
            foreach ($payments as $p) {
                fputcsv($fp, [$p['paid_at'], $p['patient_name'] ?? '', $p['invoice_no'] ?? '', (float)($p['amount'] ?? 0)]);
            }
            rewind($fp); $csv = stream_get_contents($fp); fclose($fp);
            $zip->addFromString('payments.csv', $csv);

            // aging.csv
            $fp = fopen('php://temp', 'w');
            fputcsv($fp, ['Bucket','Amount']);
            foreach ($aging as $bucket=>$amt) { fputcsv($fp, [$bucket, $amt]); }
            rewind($fp); $csv = stream_get_contents($fp); fclose($fp);
            $zip->addFromString('aging.csv', $csv);

            $zip->close();
            $filename = 'finance_backup_' . date('Ymd_His') . '.zip';
            $data = file_get_contents($tmp); @unlink($tmp);
            AuditLogger::log('finance_export_zip', 'invoices=' . count($invoices) . ' payments=' . count($payments));
            return service('response')
                ->setHeader('Content-Type', 'application/zip')
                ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
                ->setBody($data);
        }

        // Fallback: return payments CSV
        $fp = fopen('php://temp', 'w');
        fputcsv($fp, ['Date', 'Patient', 'Invoice', 'Amount']);
        foreach ($payments as $p) {
            fputcsv($fp, [$p['paid_at'], $p['patient_name'] ?? '', $p['invoice_no'] ?? '', (float)($p['amount'] ?? 0)]);
        }
        rewind($fp); $csv = stream_get_contents($fp); fclose($fp);
        $filename = 'statement_' . date('Ymd_His') . '.csv';
        AuditLogger::log('finance_export_csv_fallback', 'payments=' . count($payments));
        return service('response')
            ->setHeader('Content-Type', 'text/csv')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setBody($csv);
    }

    public function setupLabTestColumns()
    {
        helper('url');
        $db = \Config\Database::connect();
        
        try {
            // Check if table exists
            $tables = $db->listTables();
            $tableExists = in_array('lab_tests', $tables);
            
            if (!$tableExists) {
                // Create table with all columns
                $sql = "CREATE TABLE IF NOT EXISTS `lab_tests` (
                  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                  `test_number` varchar(20) NOT NULL,
                  `patient_id` int(11) UNSIGNED NOT NULL,
                  `doctor_id` int(11) UNSIGNED NOT NULL,
                  `test_type` varchar(100) NOT NULL,
                  `test_name` varchar(200) NOT NULL,
                  `test_category` enum('blood','urine','imaging','pathology','microbiology','other') NOT NULL,
                  `requires_specimen` TINYINT(1) DEFAULT 1 COMMENT '1 = requires specimen, 0 = no specimen needed',
                  `accountant_approved` TINYINT(1) DEFAULT 0 COMMENT '1 = approved by accountant, 0 = pending approval',
                  `accountant_approved_by` INT(11) UNSIGNED NULL,
                  `accountant_approved_at` DATETIME NULL,
                  `requested_date` datetime NOT NULL,
                  `sample_collected_date` datetime DEFAULT NULL,
                  `result_date` datetime DEFAULT NULL,
                  `status` enum('requested','sample_collected','in_progress','completed','cancelled') NOT NULL DEFAULT 'requested',
                  `results` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
                  `normal_range` text DEFAULT NULL,
                  `interpretation` text DEFAULT NULL,
                  `lab_technician_id` int(11) UNSIGNED DEFAULT NULL,
                  `branch_id` int(11) UNSIGNED NOT NULL,
                  `priority` enum('routine','urgent','stat') NOT NULL DEFAULT 'routine',
                  `cost` decimal(8,2) NOT NULL DEFAULT 0.00,
                  `notes` text DEFAULT NULL,
                  `created_at` datetime DEFAULT NULL,
                  `updated_at` datetime DEFAULT NULL,
                  `deleted_at` datetime DEFAULT NULL,
                  PRIMARY KEY (`id`),
                  UNIQUE KEY `test_number` (`test_number`),
                  KEY `patient_id` (`patient_id`),
                  KEY `doctor_id` (`doctor_id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";
                
                $db->query($sql);
                return redirect()->to(site_url('accountant/lab-test-approvals'))->with('success', 'Lab tests table created successfully with all required columns!');
            }
            
            // Table exists, check if columns exist
            $columns = $db->getFieldNames('lab_tests');
            $hasApprovalColumns = in_array('accountant_approved', $columns);
            
            if ($hasApprovalColumns) {
                return redirect()->to(site_url('accountant/lab-test-approvals'))->with('success', 'Columns already exist!');
            }
            
            // Add columns to existing table
            $sql = "ALTER TABLE `lab_tests` 
                    ADD COLUMN `requires_specimen` TINYINT(1) DEFAULT 1 COMMENT '1 = requires specimen, 0 = no specimen needed' AFTER `test_category`,
                    ADD COLUMN `accountant_approved` TINYINT(1) DEFAULT 0 COMMENT '1 = approved by accountant, 0 = pending approval' AFTER `requires_specimen`,
                    ADD COLUMN `accountant_approved_by` INT(11) UNSIGNED NULL AFTER `accountant_approved`,
                    ADD COLUMN `accountant_approved_at` DATETIME NULL AFTER `accountant_approved_by`";
            
            $db->query($sql);
            
            return redirect()->to(site_url('accountant/lab-test-approvals'))->with('success', 'Database columns added successfully!');
            
        } catch (\Exception $e) {
            return redirect()->to(site_url('accountant/lab-test-approvals'))->with('error', 'Error: ' . $e->getMessage() . '. Please check your database connection and make sure you are using the correct database.');
        }
    }

    public function labTestApprovals()
    {
        helper('url');
        $labTestModel = model('App\\Models\\LabTestModel');
        $billingModel = new BillingModel();
        
        // Check if columns exist
        $db = \Config\Database::connect();
        $columns = $db->getFieldNames('lab_tests');
        $hasApprovalColumns = in_array('accountant_approved', $columns);
        
        if (!$hasApprovalColumns) {
            return view('Accountant/lab_test_approvals', [
                'tests' => [],
                'error' => 'Database columns not found. Please run the migration or SQL script to add the required columns.',
            ]);
        }
        
        // Get pending lab tests that need approval (not yet approved)
        $pendingTests = $labTestModel
            ->select('lab_tests.*, patients.first_name, patients.last_name, patients.patient_id as patient_code, users.username as doctor_name')
            ->join('patients', 'patients.id = lab_tests.patient_id', 'left')
            ->join('users', 'users.id = lab_tests.doctor_id', 'left')
            ->where('lab_tests.accountant_approved', 0)
            ->where('lab_tests.status !=', 'cancelled')
            ->orderBy('lab_tests.requested_date', 'DESC')
            ->findAll(100);
        
        // Check payment status for each test
        $testsWithPaymentStatus = [];
        foreach ($pendingTests as $test) {
            $patientId = $test['patient_id'];
            $billings = $billingModel
                ->where('patient_id', $patientId)
                ->where('payment_status !=', 'cancelled')
                ->orderBy('created_at', 'DESC')
                ->findAll();
            
            $hasPaid = false;
            foreach ($billings as $billing) {
                if ($billing['payment_status'] === 'paid') {
                    $hasPaid = true;
                    break;
                }
            }
            
            $testsWithPaymentStatus[] = [
                'test' => $test,
                'has_paid' => $hasPaid,
            ];
        }
        
        return view('Accountant/lab_test_approvals', [
            'tests' => $testsWithPaymentStatus,
        ]);
    }

    public function approveLabTest($testId)
    {
        helper(['url', 'form']);
        $labTestModel = model('App\\Models\\LabTestModel');
        $billingModel = new BillingModel();
        
        $test = $labTestModel->find($testId);
        if (!$test) {
            return redirect()->to(site_url('accountant/lab-test-approvals'))->with('error', 'Lab test not found.');
        }
        
        // Check if patient has paid
        $patientId = $test['patient_id'];
        $billings = $billingModel
            ->where('patient_id', $patientId)
            ->where('payment_status !=', 'cancelled')
            ->orderBy('created_at', 'DESC')
            ->findAll();
        
        $hasPaid = false;
        foreach ($billings as $billing) {
            if ($billing['payment_status'] === 'paid') {
                $hasPaid = true;
                break;
            }
        }
        
        if (!$hasPaid) {
            return redirect()->to(site_url('accountant/lab-test-approvals'))->with('error', 'Cannot approve. Patient must pay registration fee first.');
        }
        
        // Approve the test and set status based on specimen requirement
        // If requires_specimen = 1: status = 'requested' (goes to nurse for collection)
        // If requires_specimen = 0: status = 'requested' (goes directly to lab staff)
        $statusToSet = 'requested'; // Default for tests requiring specimen
        if (isset($test['requires_specimen']) && (int)$test['requires_specimen'] === 0) {
            $statusToSet = 'requested'; // For tests not requiring specimen, also 'requested' but lab staff will see it directly
        }
        
        $labTestModel->update($testId, [
            'accountant_approved' => 1,
            'accountant_approved_by' => session('user_id') ?: 0,
            'accountant_approved_at' => date('Y-m-d H:i:s'),
            'status' => $statusToSet, // Set status based on specimen requirement
        ]);
        
        return redirect()->to(site_url('accountant/lab-test-approvals'))->with('success', 'Lab test approved successfully.');
    }

    public function rejectLabTest($testId)
    {
        helper(['url', 'form']);
        $labTestModel = model('App\\Models\\LabTestModel');
        
        $test = $labTestModel->find($testId);
        if (!$test) {
            return redirect()->to(site_url('accountant/lab-test-approvals'))->with('error', 'Lab test not found.');
        }
        
        // Reject by cancelling
        $labTestModel->update($testId, [
            'status' => 'cancelled',
        ]);
        
        return redirect()->to(site_url('accountant/lab-test-approvals'))->with('success', 'Lab test rejected and cancelled.');
    }

    public function approveCharge($id)
    {
        helper('url');
        $billingModel = new BillingModel();
        
        $charge = $billingModel->find($id);
        if (!$charge) {
            return redirect()->to(site_url('accountant/pending-charges'))->with('error', 'Charge not found.');
        }
        
        if ($charge['payment_status'] !== 'pending') {
            return redirect()->to(site_url('accountant/pending-charges'))->with('error', 'Only pending charges can be approved.');
        }
        
        // Approve by changing status to partial (approved but not yet paid)
        $billingModel->update($id, [
            'payment_status' => 'partial',
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        
        return redirect()->to(site_url('accountant/pending-charges'))->with('success', 'Charge approved successfully.');
    }

    public function payCharge($id)
    {
        helper('url');
        $billingModel = new BillingModel();
        $paymentModel = new PaymentModel();
        
        $charge = $billingModel->find($id);
        if (!$charge) {
            return redirect()->to(site_url('accountant/pending-charges'))->with('error', 'Charge not found.');
        }
        
        if ($charge['payment_status'] === 'paid') {
            return redirect()->to(site_url('accountant/pending-charges'))->with('error', 'Charge is already paid.');
        }
        
        if ($charge['payment_status'] === 'cancelled') {
            return redirect()->to(site_url('accountant/pending-charges'))->with('error', 'Cannot pay a cancelled charge.');
        }
        
        // Create payment record
        $paymentData = [
            'billing_id' => $id,
            'patient_id' => $charge['patient_id'],
            'amount' => $charge['total_amount'],
            'payment_method' => 'cash',
            'paid_at' => date('Y-m-d H:i:s'),
            'created_by' => session('user_id') ?: 0,
        ];
        $paymentModel->insert($paymentData);
        
        // Update billing status
        $billingModel->update($id, [
            'payment_status' => 'paid',
            'balance' => 0,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        
        return redirect()->to(site_url('accountant/pending-charges'))->with('success', 'Charge marked as paid successfully.');
    }

    public function cancelCharge($id)
    {
        helper('url');
        $billingModel = new BillingModel();
        
        $charge = $billingModel->find($id);
        if (!$charge) {
            return redirect()->to(site_url('accountant/pending-charges'))->with('error', 'Charge not found.');
        }
        
        if ($charge['payment_status'] === 'paid') {
            return redirect()->to(site_url('accountant/pending-charges'))->with('error', 'Cannot cancel a paid charge.');
        }
        
        if ($charge['payment_status'] === 'cancelled') {
            return redirect()->to(site_url('accountant/pending-charges'))->with('error', 'Charge is already cancelled.');
        }
        
        // Cancel the charge
        $billingModel->update($id, [
            'payment_status' => 'cancelled',
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        
        return redirect()->to(site_url('accountant/pending-charges'))->with('success', 'Charge cancelled successfully.');
    }

    public function viewBilling($id)
    {
        helper('url');

        $id = (int) $id;
        if ($id <= 0) {
            return redirect()->to(site_url('accountant/billing'))->with('error', 'Invalid invoice.');
        }

        $billingModel = new BillingModel();
        $itemModel    = new BillingItemModel();
        $paymentModel = new PaymentModel();
        $patientModel = new PatientModel();

        $billing = $billingModel->find($id);
        if (!$billing) {
            return redirect()->to(site_url('accountant/billing'))->with('error', 'Invoice not found.');
        }

        $patient = null;
        if (!empty($billing['patient_id'])) {
            $patient = $patientModel->find((int) $billing['patient_id']);
        }

        $items = $itemModel
            ->where('billing_id', $billing['id'])
            ->orderBy('id', 'ASC')
            ->findAll();

        $payments = $paymentModel
            ->where('billing_id', $billing['id'])
            ->orderBy('paid_at', 'ASC')
            ->findAll();

        $totalPaid = 0.0;
        foreach ($payments as $p) {
            $totalPaid += (float) ($p['amount'] ?? 0);
        }

        $insuranceCoverage = 0.0;
        if (!empty($billing['insurance_claim_number'])) {
            $insuranceCoverage = max(0.0, (float) (($billing['total_amount'] ?? 0) - ($billing['balance'] ?? 0) - $totalPaid));
        }

        return view('Accountant/billing_view', [
            'billing'            => $billing,
            'patient'            => $patient ?? [],
            'items'              => $items,
            'payments'           => $payments,
            'totalPaid'          => $totalPaid,
            'insuranceCoverage'  => $insuranceCoverage,
        ]);
    }

    /**
     * View consolidated bill for a patient (all charges in one bill)
     */
    public function viewConsolidatedBill($patientId)
    {
        helper('url');
        
        $patientId = (int) $patientId;
        $userRole = session('role');
        $isAdmin = ($userRole === 'admin');
        
        // Determine redirect URL based on user role
        $redirectUrl = $isAdmin ? site_url('admin/patients') : site_url('accountant/billing');
        
        if ($patientId <= 0) {
            return redirect()->to($redirectUrl)->with('error', 'Invalid patient ID.');
        }

        $billingModel = new BillingModel();
        $patientModel = new PatientModel();
        $itemModel = new BillingItemModel();
        $paymentModel = new PaymentModel();

        $patient = $patientModel->find($patientId);
        if (!$patient) {
            return redirect()->to($redirectUrl)->with('error', 'Patient not found.');
        }

        // Get or create active consolidated bill
        $billing = $billingModel->getConsolidatedBill($patientId);
        
        if (!$billing) {
            return redirect()->to($redirectUrl)->with('error', 'Could not retrieve patient bill.');
        }

        $items = $itemModel
            ->where('billing_id', $billing['id'])
            ->orderBy('created_at', 'ASC')
            ->findAll();

        $payments = $paymentModel
            ->where('billing_id', $billing['id'])
            ->orderBy('paid_at', 'ASC')
            ->findAll();

        $totalPaid = 0.0;
        foreach ($payments as $p) {
            $totalPaid += (float) ($p['amount'] ?? 0);
        }

        $insuranceCoverage = 0.0;
        if (!empty($billing['insurance_claim_number'])) {
            $insuranceCoverage = max(0.0, (float) (($billing['total_amount'] ?? 0) - ($billing['balance'] ?? 0) - $totalPaid));
        }

        return view('Accountant/billing_view', [
            'billing'            => $billing,
            'patient'            => $patient,
            'items'              => $items,
            'payments'           => $payments,
            'totalPaid'          => $totalPaid,
            'insuranceCoverage'  => $insuranceCoverage,
            'isConsolidated'     => true,
        ]);
    }

    /**
     * List all patients with their consolidated bills (Patient-Based Billing Dashboard)
     */
    public function consolidatedBills()
    {
        helper('url');
        $billingModel = new BillingModel();
        $patientModel = new PatientModel();
        $paymentModel = new PaymentModel();

        // Get status filter from query parameter
        $statusFilter = $this->request->getGet('status') ?? 'all';

        // Get all active patients
        $patients = $patientModel
            ->where('is_active', 1)
            ->orderBy('last_name', 'ASC')
            ->orderBy('first_name', 'ASC')
            ->findAll();

        // Get consolidated bill details for each patient
        $consolidatedBills = [];
        $totalAmount = 0;
        $totalPaid = 0;
        $totalBalance = 0;
        
        foreach ($patients as $patient) {
            $patientId = $patient['id'];
            
            // Get or create consolidated bill for this patient
            $consolidatedBill = $billingModel->getConsolidatedBill($patientId);
            
            if ($consolidatedBill) {
                $billStatus = strtolower($consolidatedBill['payment_status'] ?? 'pending');
                
                // Apply status filter
                if ($statusFilter !== 'all') {
                    if ($statusFilter === 'pending' && $billStatus !== 'pending') continue;
                    if ($statusFilter === 'partial' && $billStatus !== 'partial') continue;
                    if ($statusFilter === 'paid' && $billStatus !== 'paid') continue;
                    if ($statusFilter === 'overdue' && $billStatus !== 'overdue') continue;
                }
                
                // Check if patient is inpatient or outpatient
                $isInpatient = ($patient['admission_type'] === 'admission' && !empty($patient['assigned_room_id']));
                
                $totalAmount += (float)($consolidatedBill['total_amount'] ?? 0);
                $totalPaid += (float)($consolidatedBill['paid_amount'] ?? 0);
                $totalBalance += (float)($consolidatedBill['balance'] ?? 0);
                
                $consolidatedBills[] = [
                    'patient_id' => $patientId,
                    'patient_name' => trim($patient['first_name'] . ' ' . $patient['last_name']),
                    'patient_code' => $patient['patient_id'] ?? 'N/A',
                    'phone' => $patient['phone'] ?? 'N/A',
                    'patient_type' => $isInpatient ? 'Inpatient' : 'Outpatient',
                    'is_inpatient' => $isInpatient,
                    'bill' => $consolidatedBill,
                ];
            } elseif ($statusFilter === 'all' || $statusFilter === 'pending') {
                // Include patients without bills in 'all' or 'pending' view
                $isInpatient = ($patient['admission_type'] === 'admission' && !empty($patient['assigned_room_id']));
                $consolidatedBills[] = [
                    'patient_id' => $patientId,
                    'patient_name' => trim($patient['first_name'] . ' ' . $patient['last_name']),
                    'patient_code' => $patient['patient_id'] ?? 'N/A',
                    'phone' => $patient['phone'] ?? 'N/A',
                    'patient_type' => $isInpatient ? 'Inpatient' : 'Outpatient',
                    'is_inpatient' => $isInpatient,
                    'bill' => null,
                ];
            }
        }

        // Count by status
        $statusCounts = [
            'all' => count($consolidatedBills),
            'pending' => 0,
            'partial' => 0,
            'paid' => 0,
            'overdue' => 0,
        ];
        
        foreach ($consolidatedBills as $billData) {
            if ($billData['bill']) {
                $status = strtolower($billData['bill']['payment_status'] ?? 'pending');
                if (isset($statusCounts[$status])) {
                    $statusCounts[$status]++;
                }
            } else {
                $statusCounts['pending']++;
            }
        }

        return view('Accountant/consolidated_bills', [
            'bills' => $consolidatedBills,
            'statusFilter' => $statusFilter,
            'statusCounts' => $statusCounts,
            'totalAmount' => $totalAmount,
            'totalPaid' => $totalPaid,
            'totalBalance' => $totalBalance,
        ]);
    }

    /**
     * Add a charge to a patient's consolidated bill
     */
    public function addCharge($patientId)
    {
        helper(['url', 'form']);
        
        if ($this->request->getMethod() === 'post') {
            $billingModel = new BillingModel();
            $billingItemModel = new BillingItemModel();
            
            $itemType = $this->request->getPost('item_type');
            $itemName = $this->request->getPost('item_name');
            $description = $this->request->getPost('description');
            $quantity = (float)$this->request->getPost('quantity');
            $unitPrice = (float)$this->request->getPost('unit_price');
            
            if (empty($itemName) || $quantity <= 0 || $unitPrice <= 0) {
                return redirect()->back()->with('error', 'Please fill all required fields with valid values.')->withInput();
            }
            
            // Get or create active bill
            $bill = $billingModel->getOrCreateActiveBill($patientId, 1, session('user_id'));
            
            // Add item
            $billingItemModel->insert([
                'billing_id' => $bill['id'],
                'item_type' => $itemType ?? 'other',
                'item_name' => $itemName,
                'description' => $description ?? '',
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'total_price' => $quantity * $unitPrice,
            ]);
            
            // Recalculate bill
            $billingModel->recalculateBill($bill['id']);
            
            AuditLogger::log('charge_added', 'patient_id=' . $patientId . ' item=' . $itemName);
            return redirect()->to(site_url('accountant/consolidated-bill/' . $patientId))
                ->with('success', 'Charge added successfully.');
        }
        
        // GET request - show form
        $patientModel = new PatientModel();
        $patient = $patientModel->find($patientId);
        
        if (!$patient) {
            return redirect()->to(site_url('accountant/consolidated-bills'))
                ->with('error', 'Patient not found.');
        }
        
        return view('Accountant/add_charge', [
            'patient' => $patient,
        ]);
    }

    /**
     * Remove a charge from a patient's consolidated bill
     */
    public function removeCharge($itemId)
    {
        helper('url');
        $billingItemModel = new BillingItemModel();
        $billingModel = new BillingModel();
        
        $item = $billingItemModel->find($itemId);
        if (!$item) {
            return redirect()->back()->with('error', 'Charge item not found.');
        }
        
        $billingId = $item['billing_id'];
        $billingItemModel->delete($itemId);
        
        // Recalculate bill
        $billingModel->recalculateBill($billingId);
        
        // Get patient ID from billing
        $billing = $billingModel->find($billingId);
        $patientId = $billing['patient_id'] ?? null;
        
        AuditLogger::log('charge_removed', 'item_id=' . $itemId);
        
        if ($patientId) {
            return redirect()->to(site_url('accountant/consolidated-bill/' . $patientId))
                ->with('success', 'Charge removed successfully.');
        }
        
        return redirect()->back()->with('success', 'Charge removed successfully.');
    }

    /**
     * Apply insurance to a patient's consolidated bill
     */
    public function applyInsurance($patientId)
    {
        helper(['url', 'form']);
        
        if ($this->request->getMethod() === 'post') {
            $billingModel = new BillingModel();
            
            $insuranceClaimNumber = $this->request->getPost('insurance_claim_number');
            $insuranceAmount = (float)$this->request->getPost('insurance_amount');
            $notes = $this->request->getPost('notes');
            
            // Get or create active bill
            $bill = $billingModel->getOrCreateActiveBill($patientId, 1, session('user_id'));
            
            // Update insurance information
            $billingModel->update($bill['id'], [
                'insurance_claim_number' => $insuranceClaimNumber ?? null,
                'notes' => $notes ?? null,
            ]);
            
            // If insurance amount is provided, add as discount
            if ($insuranceAmount > 0) {
                $currentDiscount = (float)($bill['discount_amount'] ?? 0);
                $billingModel->update($bill['id'], [
                    'discount_amount' => $currentDiscount + $insuranceAmount,
                ]);
                $billingModel->recalculateBill($bill['id']);
            }
            
            AuditLogger::log('insurance_applied', 'patient_id=' . $patientId . ' claim=' . ($insuranceClaimNumber ?? 'n/a'));
            return redirect()->to(site_url('accountant/consolidated-bill/' . $patientId))
                ->with('success', 'Insurance applied successfully.');
        }
        
        // GET request - show form
        $patientModel = new PatientModel();
        $billingModel = new BillingModel();
        
        $patient = $patientModel->find($patientId);
        if (!$patient) {
            return redirect()->to(site_url('accountant/consolidated-bills'))
                ->with('error', 'Patient not found.');
        }
        
        $bill = $billingModel->getConsolidatedBill($patientId);
        
        return view('Accountant/apply_insurance', [
            'patient' => $patient,
            'bill' => $bill,
        ]);
    }

    /**
     * Accept payment for a patient's consolidated bill
     */
    public function acceptPayment($patientId)
    {
        helper(['url', 'form']);
        
        if ($this->request->getMethod() === 'post') {
            $billingModel = new BillingModel();
            $paymentModel = new PaymentModel();
            $patientModel = new PatientModel();
            
            $amount = (float)$this->request->getPost('amount');
            $paymentMethod = $this->request->getPost('payment_method') ?? 'cash';
            $paymentDate = $this->request->getPost('payment_date') ?? date('Y-m-d H:i:s');
            $transactionId = $this->request->getPost('transaction_id');
            $notes = $this->request->getPost('notes');
            
            if ($amount <= 0) {
                return redirect()->back()->with('error', 'Payment amount must be greater than zero.')->withInput();
            }
            
            // Get patient and bill
            $patient = $patientModel->find($patientId);
            if (!$patient) {
                return redirect()->back()->with('error', 'Patient not found.')->withInput();
            }
            
            $bill = $billingModel->getConsolidatedBill($patientId);
            if (!$bill) {
                return redirect()->back()->with('error', 'Bill not found.')->withInput();
            }
            
            // Check if payment exceeds balance
            $balance = (float)($bill['balance'] ?? 0);
            if ($amount > $balance) {
                return redirect()->back()
                    ->with('error', 'Payment amount ($' . number_format($amount, 2) . ') exceeds balance ($' . number_format($balance, 2) . ').')
                    ->withInput();
            }
            
            // Record payment
            $paymentModel->insert([
                'billing_id' => $bill['id'],
                'patient_id' => $patientId,
                'patient_name' => trim($patient['first_name'] . ' ' . $patient['last_name']),
                'invoice_no' => $bill['invoice_number'] ?? null,
                'amount' => $amount,
                'paid_at' => $paymentDate,
                'payment_method' => $paymentMethod,
                'transaction_id' => $transactionId ?? null,
                'notes' => $notes ?? null,
                'processed_by' => session('user_id'),
                'created_by' => session('user_id'),
            ]);
            
            // Recalculate bill balance
            $billingModel->calculateBalance($bill['id']);
            
            AuditLogger::log('payment_accepted', 'patient_id=' . $patientId . ' amount=' . $amount);
            return redirect()->to(site_url('accountant/consolidated-bill/' . $patientId))
                ->with('success', 'Payment of $' . number_format($amount, 2) . ' recorded successfully.');
        }
        
        // GET request - show form
        $patientModel = new PatientModel();
        $billingModel = new BillingModel();
        
        $patient = $patientModel->find($patientId);
        if (!$patient) {
            return redirect()->to(site_url('accountant/consolidated-bills'))
                ->with('error', 'Patient not found.');
        }
        
        $bill = $billingModel->getConsolidatedBill($patientId);
        
        return view('Accountant/accept_payment', [
            'patient' => $patient,
            'bill' => $bill,
        ]);
    }

    /**
     * Print/Export consolidated bill
     */
    public function printBill($patientId)
    {
        helper('url');
        $billingModel = new BillingModel();
        $patientModel = new PatientModel();
        $billingItemModel = new BillingItemModel();
        $paymentModel = new PaymentModel();
        
        $patient = $patientModel->find($patientId);
        if (!$patient) {
            return redirect()->to(site_url('accountant/consolidated-bills'))
                ->with('error', 'Patient not found.');
        }
        
        $bill = $billingModel->getConsolidatedBill($patientId);
        if (!$bill) {
            return redirect()->to(site_url('accountant/consolidated-bills'))
                ->with('error', 'Bill not found.');
        }
        
        $items = $billingItemModel
            ->where('billing_id', $bill['id'])
            ->orderBy('created_at', 'ASC')
            ->findAll();
        
        $payments = $paymentModel
            ->where('billing_id', $bill['id'])
            ->orderBy('paid_at', 'ASC')
            ->findAll();
        
        $totalPaid = 0.0;
        foreach ($payments as $p) {
            $totalPaid += (float)($p['amount'] ?? 0);
        }
        
        return view('Accountant/print_bill', [
            'billing' => $bill,
            'patient' => $patient,
            'items' => $items,
            'payments' => $payments,
            'totalPaid' => $totalPaid,
        ]);
    }
}
