<?php
namespace App\Models;

use CodeIgniter\Model;

class InventoryModel extends Model
{
    protected $table            = 'inventory';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $deletedField     = 'deleted_at';

    protected $allowedFields    = [
        'medicine_id','branch_id','batch_number','expiry_date','quantity_in_stock','minimum_stock_level','maximum_stock_level','reorder_level','location','last_updated_by'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'medicine_id' => 'required|integer',
        'branch_id' => 'required|integer',
        'quantity_in_stock' => 'required|integer|greater_than_equal_to[0]',
        'minimum_stock_level' => 'permit_empty|integer|greater_than_equal_to[0]',
        'maximum_stock_level' => 'permit_empty|integer|greater_than[0]',
        'reorder_level' => 'permit_empty|integer|greater_than_equal_to[0]',
    ];

    // Relationships
    public function getMedicine()
    {
        return $this->db->table('medicines')
            ->where('id', $this->medicine_id ?? null)
            ->get()
            ->getRowArray();
    }

    public function getBranch()
    {
        return $this->db->table('branches')
            ->where('id', $this->branch_id ?? null)
            ->get()
            ->getRowArray();
    }

    public function getLastUpdatedBy()
    {
        return $this->db->table('users')
            ->where('id', $this->last_updated_by ?? null)
            ->get()
            ->getRowArray();
    }

    // Get inventory with all related data
    public function getInventoryWithRelations($inventoryId)
    {
        $inventory = $this->find($inventoryId);
        if ($inventory) {
            $inventory['medicine'] = $this->getMedicine();
            $inventory['branch'] = $this->getBranch();
            $inventory['last_updated_by_user'] = $this->getLastUpdatedBy();
        }
        return $inventory;
    }

    // Helper methods
    public function getLowStockItems($branchId = null)
    {
        $builder = $this->where('quantity_in_stock <=', 'reorder_level', false)
                       ->orWhere('quantity_in_stock <=', 'minimum_stock_level', false);
        
        if ($branchId) {
            $builder->where('branch_id', $branchId);
        }
        
        return $builder->orderBy('quantity_in_stock', 'ASC')->findAll();
    }

    public function getExpiringItems($days = 30, $branchId = null)
    {
        $date = date('Y-m-d', strtotime("+{$days} days"));
        $builder = $this->where('expiry_date <=', $date)
                       ->where('expiry_date >=', date('Y-m-d'))
                       ->where('quantity_in_stock >', 0);
        
        if ($branchId) {
            $builder->where('branch_id', $branchId);
        }
        
        return $builder->orderBy('expiry_date', 'ASC')->findAll();
    }

    public function getBranchInventory($branchId)
    {
        return $this->where('branch_id', $branchId)
                   ->orderBy('medicine_id', 'ASC')
                   ->findAll();
    }
}
