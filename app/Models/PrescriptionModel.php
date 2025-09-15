<?php

namespace App\Models;

use CodeIgniter\Model;

class PrescriptionModel extends Model
{
    protected $table            = 'prescriptions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'patient_id', 'doctor_id', 'medication', 'dosage', 'frequency', 'start_date', 'end_date'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
