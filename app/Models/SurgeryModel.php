<?php

namespace App\Models;

use CodeIgniter\Model;

class SurgeryModel extends Model
{
    protected $table            = 'surgeries';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'surgery_number',
        'patient_id',
        'doctor_id',
        'surgery_type',
        'surgery_date',
        'surgery_time',
        'status',
        'notes'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'patient_id'    => 'required|integer',
        'doctor_id'     => 'required|integer',
        'surgery_type'  => 'required|max_length[100]',
        'surgery_date'  => 'required|valid_date',
        'surgery_time'  => 'permit_empty|valid_date[H:i]',
        'status'        => 'permit_empty|in_list[scheduled,in_progress,completed,cancelled,postponed]',
    ];

    protected $validationMessages = [
        'patient_id' => [
            'required' => 'Patient is required.',
        ],
        'doctor_id' => [
            'required' => 'Doctor is required.',
        ],
        'surgery_type' => [
            'required' => 'Surgery type is required.',
        ],
        'surgery_date' => [
            'required' => 'Surgery date is required.',
        ],
    ];

    /**
     * Generate unique surgery number
     */
    public function generateSurgeryNumber()
    {
        $prefix = 'SURG';
        $date = date('Ymd');
        $lastSurgery = $this->where('DATE(created_at)', date('Y-m-d'))->orderBy('id', 'DESC')->first();
        
        if ($lastSurgery) {
            $lastNumber = $lastSurgery['surgery_number'] ?? '';
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
     * Get surgeries with patient and doctor information
     */
    public function getSurgeriesWithDetails($doctorId = null, $status = null, $limit = null)
    {
        $builder = $this->db->table('surgeries')
            ->select('surgeries.*, 
                     patients.first_name as patient_first_name,
                     patients.last_name as patient_last_name,
                     patients.patient_id as patient_code,
                     users.first_name as doctor_first_name,
                     users.last_name as doctor_last_name')
            ->join('patients', 'patients.id = surgeries.patient_id', 'left')
            ->join('users', 'users.id = surgeries.doctor_id', 'left')
            ->orderBy('surgeries.surgery_date', 'ASC')
            ->orderBy('surgeries.surgery_time', 'ASC');

        if ($doctorId) {
            $builder->where('surgeries.doctor_id', $doctorId);
        }

        if ($status) {
            $builder->where('surgeries.status', $status);
        } else {
            // By default, show scheduled and in_progress
            $builder->whereIn('surgeries.status', ['scheduled', 'in_progress']);
        }

        if ($limit) {
            $builder->limit($limit);
        }

        return $builder->get()->getResultArray();
    }
}

