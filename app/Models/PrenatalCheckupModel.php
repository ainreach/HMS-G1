<?php

namespace App\Models;

use CodeIgniter\Model;

class PrenatalCheckupModel extends Model
{
    protected $table            = 'prenatal_checkups';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'checkup_number',
        'patient_id',
        'doctor_id',
        'checkup_date',
        'gestational_age',
        'blood_pressure',
        'weight',
        'fetal_heart_rate',
        'notes'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'patient_id'    => 'required|integer',
        'doctor_id'     => 'required|integer',
        'checkup_date'  => 'required|valid_date',
        'gestational_age' => 'permit_empty|decimal',
        'blood_pressure' => 'permit_empty|max_length[20]',
        'weight'        => 'permit_empty|decimal',
        'fetal_heart_rate' => 'permit_empty|integer',
    ];

    protected $validationMessages = [
        'patient_id' => [
            'required' => 'Patient is required.',
        ],
        'doctor_id' => [
            'required' => 'Doctor is required.',
        ],
        'checkup_date' => [
            'required' => 'Checkup date is required.',
        ],
    ];

    /**
     * Generate unique checkup number
     */
    public function generateCheckupNumber()
    {
        $prefix = 'PREN';
        $date = date('Ymd');
        $lastCheckup = $this->where('DATE(created_at)', date('Y-m-d'))->orderBy('id', 'DESC')->first();
        
        if ($lastCheckup) {
            $lastNumber = $lastCheckup['checkup_number'] ?? '';
            if (preg_match('/' . $prefix . $date . '(\d+)/', $lastNumber, $matches)) {
                $sequence = (int)$matches[1] + 1;
            } else {
                $sequence = 1;
            }
        } else {
            $sequence = 1;
        }
        
        return $prefix . $date . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get prenatal checkups with patient and doctor information
     */
    public function getCheckupsWithDetails($doctorId = null, $patientId = null, $limit = null)
    {
        $builder = $this->db->table('prenatal_checkups')
            ->select('prenatal_checkups.*, 
                     patients.first_name as patient_first_name,
                     patients.last_name as patient_last_name,
                     patients.patient_id as patient_code,
                     users.first_name as doctor_first_name,
                     users.last_name as doctor_last_name')
            ->join('patients', 'patients.id = prenatal_checkups.patient_id', 'left')
            ->join('users', 'users.id = prenatal_checkups.doctor_id', 'left')
            ->orderBy('prenatal_checkups.checkup_date', 'DESC')
            ->orderBy('prenatal_checkups.created_at', 'DESC');

        if ($doctorId) {
            $builder->where('prenatal_checkups.doctor_id', $doctorId);
        }

        if ($patientId) {
            $builder->where('prenatal_checkups.patient_id', $patientId);
        }

        if ($limit) {
            $builder->limit($limit);
        }

        return $builder->get()->getResultArray();
    }
}

