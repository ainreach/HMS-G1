<?php
namespace App\Models;

use CodeIgniter\Model;

class InvoiceModel extends Model
{
    protected $table            = 'invoices';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'invoice_no', 'patient_name', 'amount', 'status', 'issued_at', 'billing_id', 'patient_id', 'due_date', 'paid_amount'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'invoice_no' => 'required|max_length[50]|is_unique[invoices.invoice_no,id,{id}]',
        'amount' => 'required|decimal|greater_than[0]',
        'status' => 'permit_empty|in_list[pending,paid,overdue,cancelled]',
        'issued_at' => 'required|valid_date',
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
        return $this->db->table('patients')
            ->where('id', $this->patient_id ?? null)
            ->get()
            ->getRowArray();
    }

    // Get invoice with all related data
    public function getInvoiceWithRelations($invoiceId)
    {
        $invoice = $this->find($invoiceId);
        if ($invoice) {
            $invoice['billing'] = $this->getBilling();
            $invoice['patient'] = $this->getPatient();
        }
        return $invoice;
    }

    // Helper methods
    public function getUnpaidInvoices($patientId = null)
    {
        $builder = $this->where('status !=', 'paid')
                       ->where('status !=', 'cancelled');
        
        if ($patientId) {
            $builder->where('patient_id', $patientId);
        }
        
        return $builder->orderBy('due_date', 'ASC')->findAll();
    }
}
