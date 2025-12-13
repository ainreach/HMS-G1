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
        'marital_status',
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
        'assigned_bed_id',
        'admission_date',
        'discharge_date',
        'admission_reason',
        'admission_notes',
        'attending_physician_id',
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

    // Relationships - Static methods that take ID as parameter
    public function getBranch($patientId = null)
    {
        if (!$patientId) {
            return null;
        }
        $patient = $this->find($patientId);
        if (!$patient || !isset($patient['branch_id'])) {
            return null;
        }
        return $this->db->table('branches')
            ->where('id', $patient['branch_id'])
            ->get()
            ->getRowArray();
    }

    public function getRoom($patientId = null)
    {
        if (!$patientId) {
            return null;
        }
        $patient = $this->find($patientId);
        if (!$patient || !isset($patient['assigned_room_id'])) {
            return null;
        }
        return $this->db->table('rooms')
            ->where('id', $patient['assigned_room_id'])
            ->get()
            ->getRowArray();
    }

    public function getAppointments($patientId = null)
    {
        if (!$patientId) {
            return [];
        }
        return $this->db->table('appointments')
            ->where('patient_id', $patientId)
            ->orderBy('appointment_date', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function getMedicalRecords($patientId = null)
    {
        if (!$patientId) {
            return [];
        }
        return $this->db->table('medical_records')
            ->where('patient_id', $patientId)
            ->orderBy('visit_date', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function getPrescriptions($patientId = null)
    {
        if (!$patientId) {
            return [];
        }
        return $this->db->table('prescriptions')
            ->where('patient_id', $patientId)
            ->orderBy('start_date', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function getLabTests($patientId = null)
    {
        if (!$patientId) {
            return [];
        }
        return $this->db->table('lab_tests')
            ->where('patient_id', $patientId)
            ->orderBy('requested_date', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function getBilling($patientId = null)
    {
        if (!$patientId) {
            return [];
        }
        return $this->db->table('billing')
            ->where('patient_id', $patientId)
            ->orderBy('bill_date', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function getDispensing($patientId = null)
    {
        if (!$patientId) {
            return [];
        }
        return $this->db->table('dispensing')
            ->where('patient_id', $patientId)
            ->orderBy('dispensed_at', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function getPayments($patientId = null)
    {
        if (!$patientId) {
            return [];
        }
        return $this->db->table('payments')
            ->join('billing', 'payments.billing_id = billing.id', 'left')
            ->where('billing.patient_id', $patientId)
            ->orderBy('payments.paid_at', 'DESC')
            ->get()
            ->getResultArray();
    }

    // Get patient with all related data
    public function getPatientWithRelations($patientId)
    {
        $patient = $this->find($patientId);
        if ($patient) {
            $patient['branch'] = $this->getBranch($patientId);
            $patient['room'] = $this->getRoom($patientId);
            $patient['appointments'] = $this->getAppointments($patientId);
            $patient['medical_records'] = $this->getMedicalRecords($patientId);
            $patient['prescriptions'] = $this->getPrescriptions($patientId);
            $patient['lab_tests'] = $this->getLabTests($patientId);
            $patient['billing'] = $this->getBilling($patientId);
            $patient['total_billing'] = array_sum(array_column($patient['billing'], 'total_amount'));
            $patient['total_paid'] = array_sum(array_column($patient['billing'], 'paid_amount'));
        }
        return $patient;
    }

    // Validation rules
    protected $validationRules = [
        'first_name' => 'required|max_length[50]',
        'last_name' => 'required|max_length[50]',
        'date_of_birth' => 'permit_empty|valid_date',
        'gender' => 'permit_empty|in_list[male,female,other]',
        'phone' => 'permit_empty|max_length[20]',
        'email' => 'permit_empty|valid_email|max_length[100]',
        'blood_type' => 'permit_empty|in_list[A+,A-,B+,B-,AB+,AB-,O+,O-]',
        'branch_id' => 'required|integer',
        'admission_type' => 'permit_empty|in_list[checkup,admission]',
    ];
}

