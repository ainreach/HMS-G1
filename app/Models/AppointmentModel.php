<?php
namespace App\Models;

use CodeIgniter\Model;

class AppointmentModel extends Model
{
    protected $table            = 'appointments';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $deletedField     = 'deleted_at';

    protected $allowedFields    = [
        'appointment_number','patient_id','doctor_id','branch_id','appointment_date','appointment_time','duration','type','status','reason','notes','created_by'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'patient_id' => 'required|integer',
        'doctor_id' => 'required|integer',
        'appointment_date' => 'required|valid_date',
        'appointment_time' => 'required',
        'status' => 'permit_empty|in_list[scheduled,confirmed,checked_in,in_progress,completed,cancelled,no_show]',
        'type' => 'permit_empty|in_list[consultation,follow_up,emergency,checkup]',
        'branch_id' => 'required|integer',
    ];

    // Relationships - Static methods that take ID as parameter
    public function getPatient($appointmentId = null)
    {
        if (!$appointmentId) {
            return null;
        }
        $appointment = $this->find($appointmentId);
        if (!$appointment || !isset($appointment['patient_id'])) {
            return null;
        }
        return $this->db->table('patients')
            ->where('id', $appointment['patient_id'])
            ->get()
            ->getRowArray();
    }

    public function getDoctor($appointmentId = null)
    {
        if (!$appointmentId) {
            return null;
        }
        $appointment = $this->find($appointmentId);
        if (!$appointment || !isset($appointment['doctor_id'])) {
            return null;
        }
        return $this->db->table('users')
            ->where('id', $appointment['doctor_id'])
            ->get()
            ->getRowArray();
    }

    public function getBranch($appointmentId = null)
    {
        if (!$appointmentId) {
            return null;
        }
        $appointment = $this->find($appointmentId);
        if (!$appointment || !isset($appointment['branch_id'])) {
            return null;
        }
        return $this->db->table('branches')
            ->where('id', $appointment['branch_id'])
            ->get()
            ->getRowArray();
    }

    public function getMedicalRecord($appointmentId = null)
    {
        if (!$appointmentId) {
            return null;
        }
        return $this->db->table('medical_records')
            ->where('appointment_id', $appointmentId)
            ->get()
            ->getRowArray();
    }

    public function getBilling($appointmentId = null)
    {
        if (!$appointmentId) {
            return [];
        }
        return $this->db->table('billing')
            ->where('appointment_id', $appointmentId)
            ->get()
            ->getResultArray();
    }

    // Get appointment with all related data
    public function getAppointmentWithRelations($appointmentId)
    {
        $appointment = $this->find($appointmentId);
        if ($appointment) {
            $appointment['patient'] = $this->getPatient($appointmentId);
            $appointment['doctor'] = $this->getDoctor($appointmentId);
            $appointment['branch'] = $this->getBranch($appointmentId);
            $appointment['medical_record'] = $this->getMedicalRecord($appointmentId);
            $appointment['billing'] = $this->getBilling($appointmentId);
        }
        return $appointment;
    }

    // Helper methods
    public function getUpcomingAppointments($doctorId = null, $patientId = null, $limit = 10)
    {
        $builder = $this->where('status !=', 'completed')
                       ->where('status !=', 'cancelled')
                       ->where('appointment_date >=', date('Y-m-d'))
                       ->orderBy('appointment_date', 'ASC')
                       ->orderBy('appointment_time', 'ASC');
        
        if ($doctorId) {
            $builder->where('doctor_id', $doctorId);
        }
        
        if ($patientId) {
            $builder->where('patient_id', $patientId);
        }
        
        return $builder->findAll($limit);
    }

    public function getTodayAppointments($doctorId = null, $branchId = null)
    {
        $builder = $this->where('appointment_date', date('Y-m-d'))
                       ->where('status !=', 'cancelled')
                       ->orderBy('appointment_time', 'ASC');
        
        if ($doctorId) {
            $builder->where('doctor_id', $doctorId);
        }
        
        if ($branchId) {
            $builder->where('branch_id', $branchId);
        }
        
        return $builder->findAll();
    }
}
