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
        'claim_no','invoice_no','billing_id','patient_name','provider','policy_no','amount_claimed','amount_approved','status','submitted_at','patient_id','notes','processed_by','processed_at'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'claim_no' => 'required|max_length[50]|is_unique[insurance_claims.claim_no,id,{id}]',
        'billing_id' => 'required|integer',
        'provider' => 'required|max_length[100]',
        'policy_no' => 'required|max_length[50]',
        'amount_claimed' => 'required|decimal|greater_than[0]',
        'status' => 'permit_empty|in_list[pending,submitted,approved,rejected,paid]',
        'submitted_at' => 'required|valid_date',
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

    // Get claim with all related data
    public function getClaimWithRelations($claimId)
    {
        $claim = $this->find($claimId);
        if ($claim) {
            $claim['billing'] = $this->getBilling();
            $claim['patient'] = $this->getPatient();
            $claim['processed_by_user'] = $this->getProcessedBy();
        }
        return $claim;
    }

    // Helper methods
    public function getPendingClaims()
    {
        return $this->where('status', 'pending')
                   ->orWhere('status', 'submitted')
                   ->orderBy('submitted_at', 'DESC')
                   ->findAll();
    }

    public function getBillingClaims($billingId)
    {
        return $this->where('billing_id', $billingId)
                   ->orderBy('submitted_at', 'DESC')
                   ->findAll();
    }
}
