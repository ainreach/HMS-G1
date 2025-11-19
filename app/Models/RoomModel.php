<?php

namespace App\Models;

use CodeIgniter\Model;

class RoomModel extends Model
{
    protected $table            = 'rooms';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'room_number','room_type','floor','capacity','current_occupancy','status','rate_per_day','features','branch_id'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Get available rooms by type
    public function getAvailableRooms($roomType = null, $branchId = 1)
    {
        $builder = $this->where('status', 'available')
                      ->where('branch_id', $branchId)
                      ->where('current_occupancy < capacity');
        
        if ($roomType) {
            $builder = $builder->where('room_type', $roomType);
        }
        
        return $builder->orderBy('room_number', 'ASC')->findAll();
    }

    // Get room by ID with details
    public function getRoomWithDetails($roomId)
    {
        return $this->find($roomId);
    }

    // Update room occupancy
    public function updateOccupancy($roomId, $change)
    {
        $room = $this->find($roomId);
        if ($room) {
            $newOccupancy = $room['current_occupancy'] + $change;
            if ($newOccupancy >= 0 && $newOccupancy <= $room['capacity']) {
                $status = ($newOccupancy >= $room['capacity']) ? 'occupied' : 'available';
                return $this->update($roomId, [
                    'current_occupancy' => $newOccupancy,
                    'status' => $status
                ]);
            }
        }
        return false;
    }

    // Get rooms by patient age (pediatrics for under 18)
    public function getRoomsByAge($age, $branchId = 1)
    {
        if ($age < 18) {
            return $this->getAvailableRooms('pediatrics', $branchId);
        }
        return $this->getAvailableRooms(null, $branchId);
    }

    // Get room statistics
    public function getRoomStats($branchId = 1)
    {
        $total = $this->where('branch_id', $branchId)->countAllResults();
        $available = $this->where('branch_id', $branchId)
                          ->where('status', 'available')
                          ->countAllResults();
        $occupied = $this->where('branch_id', $branchId)
                         ->where('status', 'occupied')
                         ->countAllResults();
        $maintenance = $this->where('branch_id', $branchId)
                             ->where('status', 'maintenance')
                             ->countAllResults();
        
        return [
            'total' => $total,
            'available' => $available,
            'occupied' => $occupied,
            'maintenance' => $maintenance,
            'occupancy_rate' => $total > 0 ? round(($occupied / $total) * 100, 1) : 0
        ];
    }

    // Get all rooms with pagination
    public function getAllRooms($branchId = 1, $limit = 50, $offset = 0)
    {
        return $this->where('branch_id', $branchId)
                    ->orderBy('floor', 'ASC')
                    ->orderBy('room_number', 'ASC')
                    ->findAll($limit, $offset);
    }
}