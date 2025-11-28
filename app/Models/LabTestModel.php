<?php

namespace App\Models;

use CodeIgniter\Model;

class LabTestModel extends Model
{
    protected $table            = 'lab_tests'; // Update this to your actual lab tests table name
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        // Add your lab test fields here, for example:
        'test_name', 'description', 'price', 'status', 'created_at', 'updated_at'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    // Add any other necessary configurations
}