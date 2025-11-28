<?php

namespace App\Models;

use CodeIgniter\Model;

class DispensingModel extends Model
{
    protected $table = 'dispensing';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'patient_id',
        'medicine_id',
        'quantity',
        'dispensed_by',
        'dispensed_at',
        'prescription_id',
        'notes',
        'created_at',
        'updated_at'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}