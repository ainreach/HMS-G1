<?php

namespace App\Models;

use CodeIgniter\Model;

class LabTestModel extends Model
{
    protected $table            = 'lab_tests';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'test_number','patient_id','doctor_id','test_type','test_name','test_category','requested_date','result_date','status','priority','notes','results','branch_id'
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
}
