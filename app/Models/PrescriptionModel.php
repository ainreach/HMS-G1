<?php

namespace App\Models;

use CodeIgniter\Model;

class PrescriptionModel extends Model
{
    protected $table            = 'prescriptions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $deletedField     = 'deleted_at';

    protected $allowedFields    = [
        'patient_id', 'doctor_id', 'medication', 'dosage', 'frequency', 'start_date', 'end_date', 'instructions', 'status'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'patient_id' => 'required|integer',
        'doctor_id' => 'required|integer',
        'medication' => 'required|max_length[255]',
        'dosage' => 'required|max_length[100]',
        'frequency' => 'required|max_length[100]',
        'start_date' => 'required|valid_date',
        'status' => 'permit_empty|in_list[pending,approved,prepared,dispensed,administered,cancelled]',
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

    public function getDispensing()
    {
        return $this->db->table('dispensing')
            ->where('prescription_id', $this->id ?? null)
            ->get()
            ->getResultArray();
    }

    // Get prescription with all related data
    public function getPrescriptionWithRelations($prescriptionId)
    {
        $prescription = $this->find($prescriptionId);
        if ($prescription) {
            $prescription['patient'] = $this->getPatient();
            $prescription['doctor'] = $this->getDoctor();
            $prescription['dispensing'] = $this->getDispensing();
        }
        return $prescription;
    }

    // Helper methods
    public function getActivePrescriptions($patientId = null, $doctorId = null)
    {
        $builder = $this->where('status', 'active')
                       ->where('end_date >=', date('Y-m-d'))
                       ->orderBy('start_date', 'DESC');
        
        if ($patientId) {
            $builder->where('patient_id', $patientId);
        }
        
        if ($doctorId) {
            $builder->where('doctor_id', $doctorId);
        }
        
        return $builder->findAll();
    }

    public function getPatientPrescriptions($patientId, $limit = null)
    {
        $builder = $this->where('patient_id', $patientId)
                       ->orderBy('start_date', 'DESC');
        
        return $limit ? $builder->findAll($limit) : $builder->findAll();
    }
}
