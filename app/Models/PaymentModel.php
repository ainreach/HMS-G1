<?php
namespace App\Models;

use CodeIgniter\Model;

class PaymentModel extends Model
{
    protected $table            = 'payments';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'billing_id', 'patient_id', 'patient_name', 'invoice_no', 'amount', 'paid_at', 'payment_method', 'transaction_id', 'notes', 'processed_by', 'created_by'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'billing_id' => 'required|integer',
        'amount' => 'required|decimal|greater_than[0]',
        'paid_at' => 'required|valid_date',
        'payment_method' => 'permit_empty|in_list[cash,card,cheque,bank_transfer,insurance,other]',
    ];

    // Relationships
    public function getBilling()
    {
        return $this->db->table('billing')
            ->where('id', $this->billing_id ?? null)
            ->get()
            ->getRowArray();
    }

    public function getPatient()
    {
        $billing = $this->getBilling();
        if ($billing && isset($billing['patient_id'])) {
            return $this->db->table('patients')
                ->where('id', $billing['patient_id'])
                ->get()
                ->getRowArray();
        }
        return null;
    }

    public function getProcessedBy()
    {
        return $this->db->table('users')
            ->where('id', $this->processed_by ?? null)
            ->get()
            ->getRowArray();
    }

    // Get payment with all related data
    public function getPaymentWithRelations($paymentId)
    {
        $payment = $this->find($paymentId);
        if ($payment) {
            $payment['billing'] = $this->getBilling();
            $payment['patient'] = $this->getPatient();
            $payment['processed_by_user'] = $this->getProcessedBy();
        }
        return $payment;
    }

    // Helper methods
    public function getBillingPayments($billingId)
    {
        return $this->where('billing_id', $billingId)
                   ->orderBy('paid_at', 'DESC')
                   ->findAll();
    }

    public function getTotalPaidForBilling($billingId)
    {
        $result = $this->where('billing_id', $billingId)
                      ->selectSum('amount')
                      ->get()
                      ->getRowArray();
        
        return $result['amount'] ?? 0;
    }
}
