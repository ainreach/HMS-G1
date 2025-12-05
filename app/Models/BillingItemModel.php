<?php
namespace App\Models;

use CodeIgniter\Model;

class BillingItemModel extends Model
{
    protected $table            = 'billing_items';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $deletedField     = 'deleted_at';

    protected $allowedFields    = [
        'billing_id', 'item_type', 'item_name', 'description', 'quantity', 'unit_price', 'total_price',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'billing_id' => 'required|integer',
        'item_type' => 'required|in_list[consultation,medication,lab_test,room,procedure,other]',
        'item_name' => 'required|max_length[255]',
        'quantity' => 'required|decimal|greater_than[0]',
        'unit_price' => 'required|decimal|greater_than_equal_to[0]',
        'total_price' => 'required|decimal|greater_than_equal_to[0]',
    ];

    // Relationships
    public function getBilling()
    {
        return $this->db->table('billing')
            ->where('id', $this->billing_id ?? null)
            ->get()
            ->getRowArray();
    }

    // Before insert callback to calculate total_price
    protected $beforeInsert = ['calculateTotalPrice'];
    protected $beforeUpdate = ['calculateTotalPrice'];

    protected function calculateTotalPrice(array $data)
    {
        if (isset($data['data']['quantity']) && isset($data['data']['unit_price'])) {
            $data['data']['total_price'] = $data['data']['quantity'] * $data['data']['unit_price'];
        }
        return $data;
    }
}
