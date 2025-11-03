<?php
namespace App\Models;

use CodeIgniter\Model;

class LabTestModel extends Model
{
    protected $table            = 'lab_tests';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $allowedFields    = [
        'test_number','patient_id','doctor_id','test_type','test_name','test_category','requested_date','sample_collected_date','result_date','status','results','normal_range','interpretation','lab_technician_id','branch_id','priority','cost','notes'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
