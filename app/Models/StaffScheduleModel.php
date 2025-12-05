<?php
namespace App\Models;

use CodeIgniter\Model;

class StaffScheduleModel extends Model
{
    protected $table            = 'staff_schedules';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $allowedFields    = [
        'user_id', 'branch_id', 'day_of_week', 'start_time', 'end_time', 'is_active'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $useSoftDeletes   = true;
    protected $deletedField     = 'deleted_at';

    protected $validationRules = [
        'user_id' => 'required|integer',
        'branch_id' => 'required|integer',
        'day_of_week' => 'required|integer|greater_than_equal_to[0]|less_than[7]',
        'start_time' => 'required',
        'end_time' => 'required',
        'is_active' => 'permit_empty|in_list[0,1]',
    ];

    // Relationships
    public function getUser()
    {
        return $this->db->table('users')
            ->where('id', $this->user_id ?? null)
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

    // Get schedule with all related data
    public function getScheduleWithRelations($scheduleId)
    {
        $schedule = $this->find($scheduleId);
        if ($schedule) {
            $schedule['user'] = $this->getUser();
            $schedule['branch'] = $this->getBranch();
        }
        return $schedule;
    }

    // Helper methods
    public function getUserSchedules($userId, $branchId = null)
    {
        $builder = $this->where('user_id', $userId)
                       ->where('is_active', 1)
                       ->orderBy('day_of_week', 'ASC')
                       ->orderBy('start_time', 'ASC');
        
        if ($branchId) {
            $builder->where('branch_id', $branchId);
        }
        
        return $builder->findAll();
    }

    public function getTodaySchedule($userId, $branchId = null)
    {
        $dayOfWeek = date('w'); // 0 = Sunday, 6 = Saturday
        $builder = $this->where('user_id', $userId)
                       ->where('day_of_week', $dayOfWeek)
                       ->where('is_active', 1);
        
        if ($branchId) {
            $builder->where('branch_id', $branchId);
        }
        
        return $builder->orderBy('start_time', 'ASC')->findAll();
    }
}
