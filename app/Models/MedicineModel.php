<?php
namespace App\Models;

use CodeIgniter\Model;

class MedicineModel extends Model
{
    protected $table            = 'medicines';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $deletedField     = 'deleted_at';

    protected $allowedFields = [
        'name', 'stock', 'price', 'retail_price', 'unit', 'is_active', 'expiration_date', 'description', 'manufacturer', 'category'
    ];

    protected $validationRules = [
        'name'         => 'required|min_length[3]|max_length[255]',
        'price'        => 'required|numeric|greater_than_equal_to[0]',
        'retail_price' => 'required|numeric|greater_than_equal_to[0]',
        'stock'        => 'required|integer|greater_than_equal_to[0]',
        'unit' => 'permit_empty|max_length[50]',
        'is_active' => 'permit_empty|in_list[0,1]',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Relationships
    public function getInventory($branchId = null)
    {
        $builder = $this->db->table('inventory')
            ->where('medicine_id', $this->id ?? null);
        
        if ($branchId) {
            $builder->where('branch_id', $branchId);
        }
        
        return $builder->get()->getResultArray();
    }

    public function getDispensing()
    {
        return $this->db->table('dispensing')
            ->where('medicine_id', $this->id ?? null)
            ->orderBy('dispensed_at', 'DESC')
            ->get()
            ->getResultArray();
    }

    // Get medicine with all related data
    public function getMedicineWithRelations($medicineId, $branchId = null)
    {
        $medicine = $this->find($medicineId);
        if ($medicine) {
            $medicine['inventory'] = $this->getInventory($branchId);
            $medicine['dispensing'] = $this->getDispensing();
        }
        return $medicine;
    }

    // Helper methods
    public function getActiveMedicines()
    {
        return $this->where('is_active', 1)
                   ->orderBy('name', 'ASC')
                   ->findAll();
    }

    public function getLowStockMedicines($threshold = 10)
    {
        return $this->where('is_active', 1)
                   ->where('stock <=', $threshold)
                   ->orderBy('stock', 'ASC')
                   ->findAll();
    }

    public function searchMedicines($searchTerm)
    {
        return $this->where('is_active', 1)
                   ->groupStart()
                       ->like('name', $searchTerm)
                       ->orLike('description', $searchTerm)
                       ->orLike('category', $searchTerm)
                   ->groupEnd()
                   ->orderBy('name', 'ASC')
                   ->findAll();
    }
}
