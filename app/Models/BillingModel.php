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
}
