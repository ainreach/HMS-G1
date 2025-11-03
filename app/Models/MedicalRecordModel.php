<?php
namespace App\Models;

use CodeIgniter\Model;

class MedicalRecordModel extends Model
{
    protected $table            = 'medical_records';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

<<<<<<< HEAD
    protected $allowedFields    = [
        'record_number','patient_id','appointment_id','doctor_id','visit_date','chief_complaint','history_present_illness','physical_examination','vital_signs','diagnosis','treatment_plan','medications_prescribed','follow_up_instructions','next_visit_date','branch_id'
    ];

=======
    protected $allowedFields = [
        'record_number', 'patient_id', 'appointment_id', 'doctor_id', 'visit_date',
        'vital_signs', 'branch_id'
    ];

    protected $validationRules = [
        'patient_id'     => 'required|is_natural_no_zero',
        'appointment_id' => 'permit_empty|is_natural_no_zero',
        'doctor_id'      => 'permit_empty|is_natural_no_zero',
        'vital_signs'    => 'required|valid_json_string'
    ];

    protected $validationMessages = [
        'vital_signs' => [
            'valid_json_string' => 'The vital signs must be in a valid format (e.g., "BP: 120/80" or valid JSON)'
        ]
    ];

    // Let controller handle JSON encoding
>>>>>>> 2305c0b (Nurse: fix JSON validation + routes + monitoring)
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
