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
}
