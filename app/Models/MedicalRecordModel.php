<?php
namespace App\Models;

use CodeIgniter\Model;

class MedicalRecordModel extends Model
{
    protected $table            = 'medical_records';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $allowedFields    = [
        'record_number','patient_id','appointment_id','doctor_id','visit_date','chief_complaint','history_present_illness','physical_examination','vital_signs','diagnosis','treatment_plan','medications_prescribed','follow_up_instructions','next_visit_date','branch_id'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
