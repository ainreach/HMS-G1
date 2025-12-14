<?php

namespace App\Models;

use CodeIgniter\Model;

class DepartmentModel extends Model
{
    protected $table            = 'departments';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    protected $allowedFields    = [
        'name',
        'code',
        'description',
        'head_doctor_id',
        'is_active',
    ];

    protected $validationRules = [
        'name' => 'required|max_length[100]',
        'code' => 'required|max_length[20]|is_unique[departments.code,id,{id}]',
        'description' => 'permit_empty',
        'head_doctor_id' => 'permit_empty|integer',
        'is_active' => 'permit_empty|in_list[0,1]',
    ];

    /**
     * Get all active departments
     */
    public function getActiveDepartments()
    {
        return $this->where('is_active', 1)
            ->orderBy('name', 'ASC')
            ->findAll();
    }

    /**
     * Get department with head doctor information
     */
    public function getDepartmentWithHead($departmentId)
    {
        $department = $this->find($departmentId);
        if (!$department) {
            return null;
        }

        if ($department['head_doctor_id']) {
            $userModel = model('App\Models\UserModel');
            $department['head_doctor'] = $userModel->find($department['head_doctor_id']);
        } else {
            $department['head_doctor'] = null;
        }

        return $department;
    }

    /**
     * Get all departments with doctor counts
     */
    public function getDepartmentsWithStats()
    {
        $departments = $this->orderBy('name', 'ASC')->findAll();
        $userModel = model('App\Models\UserModel');

        foreach ($departments as &$dept) {
            // Count doctors in this department
            $dept['doctor_count'] = $userModel
                ->where('department_id', $dept['id'])
                ->where('role', 'doctor')
                ->where('is_active', 1)
                ->countAllResults();
            
            // Get head doctor info
            if ($dept['head_doctor_id']) {
                $dept['head_doctor'] = $userModel->find($dept['head_doctor_id']);
            } else {
                $dept['head_doctor'] = null;
            }
        }

        return $departments;
    }
    
    /**
     * Get doctors in a department
     */
    public function getDoctorsInDepartment($departmentId)
    {
        $userModel = model('App\Models\UserModel');
        return $userModel
            ->where('department_id', $departmentId)
            ->where('role', 'doctor')
            ->where('is_active', 1)
            ->orderBy('first_name', 'ASC')
            ->findAll();
    }
}

