<?php

namespace App\Models;

use CodeIgniter\Model;

class LabTestCatalogModel extends Model
{
    protected $table            = 'lab_test_catalog';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    
    protected $allowedFields    = [
        'test_name', 'test_type', 'specimen_category', 'description', 
        'normal_range', 'price', 'is_active'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'test_name' => 'required|max_length[255]',
        'test_type' => 'permit_empty|max_length[100]',
        'specimen_category' => 'permit_empty|in_list[with_specimen,without_specimen]',
        'price' => 'required|decimal|greater_than_equal_to[0]',
        'is_active' => 'permit_empty|in_list[0,1]',
    ];

    /**
     * Get all active lab tests grouped by specimen category and test type
     */
    public function getActiveTestsGroupedByCategory()
    {
        $tests = $this->where('is_active', 1)
            ->orderBy('specimen_category', 'ASC')
            ->orderBy('test_type', 'ASC')
            ->orderBy('test_name', 'ASC')
            ->findAll();

        $grouped = [];
        foreach ($tests as $test) {
            $category = $test['specimen_category'] ?? 'with_specimen';
            $type = $test['test_type'] ?? 'Other';
            
            if (!isset($grouped[$category])) {
                $grouped[$category] = [];
            }
            if (!isset($grouped[$category][$type])) {
                $grouped[$category][$type] = [];
            }
            $grouped[$category][$type][] = $test;
        }

        return $grouped;
    }

    // Relationships
    public function getLabTests($catalogId = null)
    {
        if (!$catalogId) {
            return [];
        }
        return $this->db->table('lab_tests')
            ->where('catalog_id', $catalogId)
            ->orderBy('requested_date', 'DESC')
            ->get()
            ->getResultArray();
    }

    // Get catalog with all related data
    public function getCatalogWithRelations($catalogId)
    {
        $catalog = $this->find($catalogId);
        if ($catalog) {
            $catalog['lab_tests'] = $this->getLabTests($catalogId);
            $catalog['usage_count'] = count($catalog['lab_tests']);
        }
        return $catalog;
    }

    // Helper methods
    public function getActiveTests()
    {
        return $this->where('is_active', 1)
                   ->orderBy('test_name', 'ASC')
                   ->findAll();
    }

    public function searchTests($searchTerm)
    {
        return $this->where('is_active', 1)
                   ->groupStart()
                       ->like('test_name', $searchTerm)
                       ->orLike('test_type', $searchTerm)
                       ->orLike('description', $searchTerm)
                   ->groupEnd()
                   ->orderBy('test_name', 'ASC')
                   ->findAll();
    }

    public function getTestsByCategory($specimenCategory = null)
    {
        $builder = $this->where('is_active', 1);
        
        if ($specimenCategory) {
            $builder->where('specimen_category', $specimenCategory);
        }
        
        return $builder->orderBy('test_name', 'ASC')->findAll();
    }
}

