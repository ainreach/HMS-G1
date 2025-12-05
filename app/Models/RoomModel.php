<?php

namespace App\Models;

use CodeIgniter\Model;

class RoomModel extends Model
{
    protected $table            = 'rooms';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $deletedField     = 'deleted_at';

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
                      ->where('current_occupancy < capacity');
        
        // Only filter by branch_id if it's provided and not null
        if ($branchId !== null) {
            $builder = $builder->where('branch_id', $branchId);
        }
        
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

    // Relationships
    public function getBranch()
    {
        return $this->db->table('branches')
            ->where('id', $this->branch_id ?? null)
            ->get()
            ->getRowArray();
    }

    public function getPatients()
    {
        return $this->db->table('patients')
            ->where('assigned_room_id', $this->id ?? null)
            ->where('is_active', 1)
            ->get()
            ->getResultArray();
    }

    // Get room with all related data
    public function getRoomWithRelations($roomId)
    {
        $room = $this->find($roomId);
        if ($room) {
            $room['branch'] = $this->getBranch();
            $room['patients'] = $this->getPatients();
        }
        return $room;
    }

    // Validation rules
    protected $validationRules = [
        'room_number' => 'required|max_length[20]|is_unique[rooms.room_number,id,{id}]',
        'room_type' => 'required|in_list[private,ward,icu,emergency,consultation,operating]',
        'floor' => 'required|integer|greater_than[0]',
        'capacity' => 'required|integer|greater_than[0]',
        'status' => 'permit_empty|in_list[available,occupied,maintenance,reserved]',
        'branch_id' => 'required|integer',
    ];
}