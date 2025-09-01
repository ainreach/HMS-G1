<?php
namespace App\Models;

use CodeIgniter\Model;

class MedicineModel extends Model
{
    protected $table            = 'medicines';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $allowedFields    = [
        'medicine_code','name','generic_name','brand_name','category','dosage_form','strength','unit','manufacturer','supplier','purchase_price','selling_price','requires_prescription','description','side_effects','contraindications','storage_instructions','is_active'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
