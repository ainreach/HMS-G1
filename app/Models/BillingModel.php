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
}
