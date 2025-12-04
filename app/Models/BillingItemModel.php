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
}
