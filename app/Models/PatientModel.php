<?php
namespace App\Models;

use CodeIgniter\Model;

class PatientModel extends Model
{
    protected $table            = 'patients';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'patient_id','first_name','last_name','middle_name','date_of_birth','gender','blood_type','phone','email','address','city','emergency_contact_name','emergency_contact_phone','emergency_contact_relation','insurance_provider','insurance_number','allergies','medical_history','branch_id','is_active'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
