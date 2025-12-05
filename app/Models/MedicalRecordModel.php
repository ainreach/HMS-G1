<?php
namespace App\Models;

use CodeIgniter\Model;

class MedicalRecordModel extends Model
{
    protected $table            = 'medical_records';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $deletedField     = 'deleted_at';

    protected $allowedFields    = [
        'record_number','patient_id','appointment_id','doctor_id','visit_date','chief_complaint','history_present_illness','physical_examination','vital_signs','diagnosis','treatment_plan','medications_prescribed','follow_up_instructions','next_visit_date','branch_id'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'patient_id' => 'required|integer',
        'doctor_id' => 'required|integer',
        'visit_date' => 'required|valid_date',
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

    public function getDoctor()
    {
        return $this->db->table('users')
            ->where('id', $this->doctor_id ?? null)
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

    // Get medical record with all related data
    public function getRecordWithRelations($recordId)
    {
        $record = $this->find($recordId);
        if ($record) {
            $record['patient'] = $this->getPatient();
            $record['doctor'] = $this->getDoctor();
            $record['appointment'] = $this->getAppointment();
            $record['branch'] = $this->getBranch();
        }
        return $record;
    }

    // Helper methods
    public function getPatientRecords($patientId, $limit = null)
    {
        $builder = $this->where('patient_id', $patientId)
                       ->orderBy('visit_date', 'DESC');
        
        return $limit ? $builder->findAll($limit) : $builder->findAll();
    }

    public function getDoctorRecords($doctorId, $limit = null)
    {
        $builder = $this->where('doctor_id', $doctorId)
                       ->orderBy('visit_date', 'DESC');
        
        return $limit ? $builder->findAll($limit) : $builder->findAll();
    }
}
