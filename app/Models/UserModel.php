<?php
namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';

    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $deletedField     = 'deleted_at';

    protected $allowedFields    = [
        'employee_id', 'username', 'email', 'password', 'first_name', 'last_name', 'phone',
        'role', 'branch_id', 'specialization', 'department_id', 'license_number', 'is_active', 'profile_image'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'employee_id' => 'required|max_length[20]|is_unique[users.employee_id,id,{id}]',
        'username' => 'required|max_length[50]|is_unique[users.username,id,{id}]',
        'email' => 'required|valid_email|max_length[100]|is_unique[users.email,id,{id}]',
        'password' => 'permit_empty|min_length[8]',
        'first_name' => 'required|max_length[50]',
        'last_name' => 'required|max_length[50]',
        'role' => 'required|in_list[admin,doctor,nurse,receptionist,lab_staff,pharmacist,accountant,it_staff]',
        'branch_id' => 'permit_empty|integer',
        'is_active' => 'permit_empty|in_list[0,1]',
    ];

    // Relationships - Static methods that take ID as parameter
    public function getBranch($userId = null)
    {
        if (!$userId) {
            return null;
        }
        $user = $this->find($userId);
        if (!$user || !isset($user['branch_id'])) {
            return null;
        }
        return $this->db->table('branches')
            ->where('id', $user['branch_id'])
            ->get()
            ->getRowArray();
    }

    public function getAppointments($userId = null)
    {
        if (!$userId) {
            return [];
        }
        return $this->db->table('appointments')
            ->where('doctor_id', $userId)
            ->get()
            ->getResultArray();
    }

    public function getMedicalRecords($userId = null)
    {
        if (!$userId) {
            return [];
        }
        return $this->db->table('medical_records')
            ->where('doctor_id', $userId)
            ->get()
            ->getResultArray();
    }

    public function getPrescriptions($userId = null)
    {
        if (!$userId) {
            return [];
        }
        return $this->db->table('prescriptions')
            ->where('doctor_id', $userId)
            ->get()
            ->getResultArray();
    }

    public function getLabTests($userId = null)
    {
        if (!$userId) {
            return [];
        }
        return $this->db->table('lab_tests')
            ->where('doctor_id', $userId)
            ->orWhere('lab_technician_id', $userId)
            ->get()
            ->getResultArray();
    }

    public function getStaffSchedules($userId = null)
    {
        if (!$userId) {
            return [];
        }
        return $this->db->table('staff_schedules')
            ->where('user_id', $userId)
            ->get()
            ->getResultArray();
    }

    // Helper methods
    public function getActiveUsers($branchId = null, $role = null)
    {
        $builder = $this->where('is_active', 1);
        
        if ($branchId) {
            $builder->where('branch_id', $branchId);
        }
        
        if ($role) {
            $builder->where('role', $role);
        }
        
        return $builder->orderBy('last_name', 'ASC')->findAll();
    }

    public function getDoctors($branchId = null)
    {
        return $this->getActiveUsers($branchId, 'doctor');
    }

    public function getNurses($branchId = null)
    {
        return $this->getActiveUsers($branchId, 'nurse');
    }

    public function getUserWithRelations($userId)
    {
        $user = $this->find($userId);
        if ($user) {
            $user['branch'] = $this->getBranch($userId);
            $user['appointments_count'] = count($this->getAppointments($userId));
            $user['medical_records_count'] = count($this->getMedicalRecords($userId));
        }
        return $user;
    }
}
