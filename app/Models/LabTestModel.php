<?php

namespace App\Models;

use CodeIgniter\Model;

class LabTestModel extends Model
{
    protected $table            = 'lab_tests';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $deletedField     = 'deleted_at';

    protected $allowedFields    = [
        'test_number','patient_id','doctor_id','test_type','test_name','test_category','requires_specimen','accountant_approved','accountant_approved_by','accountant_approved_at','requested_date','sample_collected_date','result_date','status','priority','notes','results','branch_id','cost','nurse_id','lab_technician_id'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Get pending lab tests for a doctor
    public function getPendingTests($doctorId, $limit = 10)
    {
        return $this->where('doctor_id', $doctorId)
                    ->where('status !=', 'completed')
                    ->orderBy('requested_date', 'DESC')
                    ->findAll($limit);
    }

    // Get completed lab tests for a doctor
    public function getCompletedTests($doctorId, $limit = 50)
    {
        return $this->where('doctor_id', $doctorId)
                    ->where('status', 'completed')
                    ->orderBy('result_date', 'DESC')
                    ->findAll($limit);
    }

    // Update test status
    public function updateTestStatus($testId, $status, $results = null)
    {
        $data = ['status' => $status];
        
        if ($status === 'completed') {
            $data['result_date'] = date('Y-m-d H:i:s');
            if ($results !== null) {
                $data['results'] = $results;
            }
        }
        
        return $this->update($testId, $data);
    }

    // Get lab test statistics for a doctor
    public function getTestStats($doctorId)
    {
        $total = $this->where('doctor_id', $doctorId)->countAllResults();
        $pending = $this->where('doctor_id', $doctorId)
                       ->where('status !=', 'completed')
                       ->countAllResults();
        $completed = $this->where('doctor_id', $doctorId)
                         ->where('status', 'completed')
                         ->countAllResults();
        
        return [
            'total' => $total,
            'pending' => $pending,
            'completed' => $completed,
            'completion_rate' => $total > 0 ? round(($completed / $total) * 100, 1) : 0
        ];
    }

    // Relationships - Static methods that take ID as parameter
    public function getPatient($testId = null)
    {
        if (!$testId) {
            return null;
        }
        $test = $this->find($testId);
        if (!$test || !isset($test['patient_id'])) {
            return null;
        }
        return $this->db->table('patients')
            ->where('id', $test['patient_id'])
            ->get()
            ->getRowArray();
    }

    public function getDoctor($testId = null)
    {
        if (!$testId) {
            return null;
        }
        $test = $this->find($testId);
        if (!$test || !isset($test['doctor_id'])) {
            return null;
        }
        return $this->db->table('users')
            ->where('id', $test['doctor_id'])
            ->get()
            ->getRowArray();
    }

    public function getTechnician($testId = null)
    {
        if (!$testId) {
            return null;
        }
        $test = $this->find($testId);
        if (!$test || !isset($test['lab_technician_id'])) {
            return null;
        }
        return $this->db->table('users')
            ->where('id', $test['lab_technician_id'])
            ->get()
            ->getRowArray();
    }

    public function getBranch($testId = null)
    {
        if (!$testId) {
            return null;
        }
        $test = $this->find($testId);
        if (!$test || !isset($test['branch_id'])) {
            return null;
        }
        return $this->db->table('branches')
            ->where('id', $test['branch_id'])
            ->get()
            ->getRowArray();
    }

    public function getBilling()
    {
        return $this->db->table('billing_items')
            ->join('billing', 'billing_items.billing_id = billing.id')
            ->where('billing_items.item_type', 'lab_test')
            ->where('billing_items.item_name', $this->test_name ?? null)
            ->where('billing.patient_id', $this->patient_id ?? null)
            ->get()
            ->getResultArray();
    }

    // Get lab test with all related data
    public function getTestWithRelations($testId)
    {
        $test = $this->find($testId);
        if ($test) {
            $test['patient'] = $this->getPatient($testId);
            $test['doctor'] = $this->getDoctor($testId);
            $test['technician'] = $this->getTechnician($testId);
            $test['branch'] = $this->getBranch($testId);
            $test['catalog'] = $this->getCatalog($testId);
        }
        return $test;
    }

    // Helper method to get catalog
    public function getCatalog($testId = null)
    {
        if (!$testId) {
            return null;
        }
        $test = $this->find($testId);
        if (!$test || !isset($test['catalog_id'])) {
            return null;
        }
        return $this->db->table('lab_test_catalog')
            ->where('id', $test['catalog_id'])
            ->get()
            ->getRowArray();
    }

    // Validation rules
    protected $validationRules = [
        'patient_id' => 'required|integer',
        'doctor_id' => 'required|integer',
        'test_name' => 'required|max_length[255]',
        'test_type' => 'permit_empty|max_length[100]',
        'status' => 'permit_empty|in_list[requested,sample_collected,in_progress,completed,cancelled,pending,collecting,processing]',
        'priority' => 'permit_empty|in_list[routine,urgent,stat,normal]',
        'branch_id' => 'required|integer',
        'cost' => 'permit_empty|numeric',
        'requires_specimen' => 'permit_empty|in_list[0,1]',
        'accountant_approved' => 'permit_empty|in_list[0,1]',
    ];
}
