<?php

namespace App\Models;

use CodeIgniter\Model;

class DispensingModel extends Model
{
    protected $table = 'dispensing';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'patient_id',
        'medicine_id',
        'quantity',
        'dispensed_by',
        'dispensed_at',
        'prescription_id',
        'notes',
        'created_at',
        'updated_at'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'patient_id' => 'required|integer',
        'medicine_id' => 'required|integer',
        'quantity' => 'required|integer|greater_than[0]',
        'dispensed_by' => 'required|integer',
        'dispensed_at' => 'required|valid_date',
    ];

    // Relationships
    public function getPatient()
    {
        return $this->db->table('patients')
            ->where('id', $this->patient_id ?? null)
            ->get()
            ->getRowArray();
    }

    public function getMedicine()
    {
        return $this->db->table('medicines')
            ->where('id', $this->medicine_id ?? null)
            ->get()
            ->getRowArray();
    }

    public function getPrescription()
    {
        return $this->db->table('prescriptions')
            ->where('id', $this->prescription_id ?? null)
            ->get()
            ->getRowArray();
    }

    public function getDispensedBy()
    {
        return $this->db->table('users')
            ->where('id', $this->dispensed_by ?? null)
            ->get()
            ->getRowArray();
    }

    // Get dispensing with all related data
    public function getDispensingWithRelations($dispensingId)
    {
        $dispensing = $this->find($dispensingId);
        if ($dispensing) {
            $dispensing['patient'] = $this->getPatient();
            $dispensing['medicine'] = $this->getMedicine();
            $dispensing['prescription'] = $this->getPrescription();
            $dispensing['dispensed_by_user'] = $this->getDispensedBy();
        }
        return $dispensing;
    }

    // Helper methods
    public function getPatientDispensing($patientId, $limit = null)
    {
        $builder = $this->where('patient_id', $patientId)
                       ->orderBy('dispensed_at', 'DESC');
        
        return $limit ? $builder->findAll($limit) : $builder->findAll();
    }

    public function getPrescriptionDispensing($prescriptionId)
    {
        return $this->where('prescription_id', $prescriptionId)
                   ->orderBy('dispensed_at', 'DESC')
                   ->findAll();
    }
}