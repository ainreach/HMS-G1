<?php

namespace App\Models;

use CodeIgniter\Model;

class BranchModel extends Model
{
    protected $table = 'branches';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'name', 'code', 'address', 'city', 'phone', 'email', 
        'is_main', 'is_active', 'created_at', 'updated_at'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $returnType = 'array';

    protected $validationRules = [
        'name' => 'required|max_length[255]',
        'code' => 'required|max_length[20]|is_unique[branches.code,id,{id}]',
        'email' => 'permit_empty|valid_email|max_length[100]',
        'is_main' => 'permit_empty|in_list[0,1]',
        'is_active' => 'permit_empty|in_list[0,1]',
    ];

    // Relationships
    public function getUsers()
    {
        return $this->db->table('users')
            ->where('branch_id', $this->id ?? null)
            ->where('is_active', 1)
            ->get()
            ->getResultArray();
    }

    public function getPatients()
    {
        return $this->db->table('patients')
            ->where('branch_id', $this->id ?? null)
            ->where('is_active', 1)
            ->get()
            ->getResultArray();
    }

    public function getRooms()
    {
        return $this->db->table('rooms')
            ->where('branch_id', $this->id ?? null)
            ->get()
            ->getResultArray();
    }

    public function getAppointments()
    {
        return $this->db->table('appointments')
            ->where('branch_id', $this->id ?? null)
            ->get()
            ->getResultArray();
    }

    // Get branch with all related data
    public function getBranchWithRelations($branchId)
    {
        $branch = $this->find($branchId);
        if ($branch) {
            $branch['users'] = $this->getUsers();
            $branch['patients'] = $this->getPatients();
            $branch['rooms'] = $this->getRooms();
            $branch['appointments'] = $this->getAppointments();
        }
        return $branch;
    }

    // Helper methods
    public function getActiveBranches()
    {
        return $this->where('is_active', 1)
                   ->orderBy('name', 'ASC')
                   ->findAll();
    }

    public function getMainBranch()
    {
        return $this->where('is_main', 1)
                   ->where('is_active', 1)
                   ->first();
    }
}
