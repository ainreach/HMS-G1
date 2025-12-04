<?php

namespace App\Models;

use CodeIgniter\Model;

class PatientModel extends Model
{
    protected $table            = 'patients';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $deletedField     = 'deleted_at';

    protected $allowedFields    = [
        'patient_id',
        'first_name',
        'last_name',
        'middle_name',
        'date_of_birth',
        'gender',
        'blood_type',
        'phone',
        'email',
        'address',
        'city',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relation',
        'insurance_provider',
        'insurance_number',
        'allergies',
        'medical_history',
        'branch_id',
        'admission_type',
        'assigned_room_id',
        'admission_date',
        'discharge_date',
        'is_active',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Get active patients
    public function getActivePatients($branchId = 1)
    {
        return $this->where('is_active', 1)
                    ->where('branch_id', $branchId)
                    ->orderBy('last_name', 'ASC')
                    ->findAll();
    }

    // Search patients
    public function searchPatients($searchTerm, $branchId = 1)
    {
        return $this->where('is_active', 1)
                    ->where('branch_id', $branchId)
                    ->groupStart()
                        ->like('first_name', $searchTerm)
                        ->orLike('last_name', $searchTerm)
                        ->orLike('patient_id', $searchTerm)
                        ->orLike('phone', $searchTerm)
                    ->groupEnd()
                    ->orderBy('last_name', 'ASC')
                    ->findAll();
    }

    // Get patient by ID
    public function getPatientById($id)
    {
        return $this->where('id', $id)
                    ->where('is_active', 1)
                    ->first();
    }

    // Get patient by patient_id
    public function getPatientByPatientId($patientId)
    {
        return $this->where('patient_id', $patientId)
                    ->where('is_active', 1)
                    ->first();
    }

    // Get admitted patients
    public function getAdmittedPatients($branchId = 1)
    {
        return $this->where('is_active', 1)
                    ->where('branch_id', $branchId)
                    ->where('admission_type', 'admission')
                    ->whereNotNull('assigned_room_id')
                    ->orderBy('admission_date', 'DESC')
                    ->findAll();
    }

    // Get recent patients
    public function getRecentPatients($limit = 5, $branchId = 1)
    {
        return $this->where('is_active', 1)
                    ->where('branch_id', $branchId)
                    ->orderBy('created_at', 'DESC')
                    ->findAll($limit);
    }

    // Get patient statistics
    public function getPatientStats($branchId = 1)
    {
        $total = $this->where('is_active', 1)
                      ->where('branch_id', $branchId)
                      ->countAllResults();
        
        $today = date('Y-m-d');
        $newToday = $this->where('is_active', 1)
                         ->where('branch_id', $branchId)
                         ->where('DATE(created_at)', $today)
                         ->countAllResults();
        
        $admitted = $this->where('is_active', 1)
                         ->where('branch_id', $branchId)
                         ->where('admission_type', 'admission')
                         ->countAllResults();
        
        return [
            'total' => $total,
            'new_today' => $newToday,
            'admitted' => $admitted,
            'outpatient' => $total - $admitted
        ];
    }
}

