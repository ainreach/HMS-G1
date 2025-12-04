<?php
namespace App\Models;

use CodeIgniter\Model;

class InsuranceClaimModel extends Model
{
    protected $table            = 'insurance_claims';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'claim_no','invoice_no','billing_id','patient_name','provider','policy_no','amount_claimed','amount_approved','status','submitted_at'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
