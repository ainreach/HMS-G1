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
        'user_id', 'branch_id', 'start_date', 'end_date', 'day_of_week', 
        'start_time', 'end_time', 'rest_day', 'is_recurring', 'is_active'
    ];

    protected $validationRules = [
        'user_id'      => 'required|numeric',
        'branch_id'    => 'required|numeric',
        'start_date'   => 'required|valid_date',
        'end_date'     => 'required|valid_date|after_date[start_date]',
        'day_of_week'  => 'required|in_list[monday,tuesday,wednesday,thursday,friday,saturday,sunday]',
        'start_time'   => 'required|valid_time',
        'end_time'     => 'required|valid_time|after_time_field[start_time]',
        'rest_day'     => 'permit_empty|in_list[monday,tuesday,wednesday,thursday,friday,saturday,sunday]',
        'is_recurring' => 'permit_empty|in_list[0,1]',
        'is_active'    => 'permit_empty|in_list[0,1]'
    ];

    protected $validationMessages = [
        'end_date' => [
            'after_date' => 'End date must be after start date.'
        ],
        'end_time' => [
            'after_time_field' => 'End time must be after start time.'
        ]
    ];

    protected $beforeInsert = ['setDefaultValues'];

    protected function setDefaultValues(array $data)
    {
        if (empty($data['data']['start_date'])) {
            $data['data']['start_date'] = date('Y-m-d');
        }
        
        if (empty($data['data']['end_date'])) {
            $endDate = new \DateTime($data['data']['start_date']);
            $endDate->modify('+1 year');
            $data['data']['end_date'] = $endDate->format('Y-m-d');
        }

        if (empty($data['data']['is_active'])) {
            $data['data']['is_active'] = 1;
        }

        if (empty($data['data']['is_recurring'])) {
            $data['data']['is_recurring'] = 1;
        }

        return $data;
    }

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
