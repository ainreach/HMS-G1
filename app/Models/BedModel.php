<?php

namespace App\Models;

use CodeIgniter\Model;

class BedModel extends Model
{
    protected $table            = 'beds';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'room_id', 'bed_number', 'bed_type', 'status', 'is_active'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Get available beds for a room
    public function getAvailableBeds($roomId)
    {
        return $this->where('room_id', $roomId)
                    ->where('status', 'available')
                    ->where('is_active', 1)
                    ->orderBy('bed_number', 'ASC')
                    ->findAll();
    }

    // Get all beds for a room
    public function getBedsByRoom($roomId)
    {
        return $this->where('room_id', $roomId)
                    ->where('is_active', 1)
                    ->orderBy('bed_number', 'ASC')
                    ->findAll();
    }

    // Get bed with room details
    public function getBedWithRoom($bedId)
    {
        $bed = $this->find($bedId);
        if ($bed) {
            $roomModel = model('App\\Models\\RoomModel');
            $bed['room'] = $roomModel->find($bed['room_id']);
        }
        return $bed;
    }

    // Validation rules
    protected $validationRules = [
        'room_id' => 'required|integer',
        'bed_number' => 'required|max_length[20]',
        'bed_type' => 'permit_empty|in_list[standard,electric,icu,pediatric]',
        'status' => 'permit_empty|in_list[available,occupied,maintenance,reserved]',
    ];
}

