<?php
/**
 * Quick script to add lab_tests columns
 * Run this file directly: http://localhost/HMS/run_lab_test_migration.php
 */

// Load CodeIgniter
require_once __DIR__ . '/vendor/autoload.php';

// Get database config
$dbConfig = require __DIR__ . '/app/Config/Database.php';
$db = $dbConfig->default;

try {
    // Connect to database
    $mysqli = new mysqli($db['hostname'], $db['username'], $db['password'], $db['database']);
    
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }
    
    echo "<h2>Adding Lab Test Columns</h2>";
    echo "<pre>";
    
    // Check if columns exist
    $result = $mysqli->query("SHOW COLUMNS FROM `lab_tests` LIKE 'requires_specimen'");
    $requiresSpecimenExists = $result->num_rows > 0;
    
    $result = $mysqli->query("SHOW COLUMNS FROM `lab_tests` LIKE 'accountant_approved'");
    $accountantApprovedExists = $result->num_rows > 0;
    
    if ($requiresSpecimenExists && $accountantApprovedExists) {
        echo "✓ All columns already exist!\n";
        echo "No changes needed.\n";
    } else {
        // Build ALTER TABLE statement
        $sql = "ALTER TABLE `lab_tests` ";
        $additions = [];
        
        if (!$requiresSpecimenExists) {
            $additions[] = "ADD COLUMN `requires_specimen` TINYINT(1) DEFAULT 1 COMMENT '1 = requires specimen, 0 = no specimen needed' AFTER `test_category`";
        }
        
        if (!$accountantApprovedExists) {
            $additions[] = "ADD COLUMN `accountant_approved` TINYINT(1) DEFAULT 0 COMMENT '1 = approved by accountant, 0 = pending approval' AFTER `requires_specimen`";
        }
        
        $result = $mysqli->query("SHOW COLUMNS FROM `lab_tests` LIKE 'accountant_approved_by'");
        if ($result->num_rows == 0) {
            $additions[] = "ADD COLUMN `accountant_approved_by` INT(11) UNSIGNED NULL AFTER `accountant_approved`";
        }
        
        $result = $mysqli->query("SHOW COLUMNS FROM `lab_tests` LIKE 'accountant_approved_at'");
        if ($result->num_rows == 0) {
            $additions[] = "ADD COLUMN `accountant_approved_at` DATETIME NULL AFTER `accountant_approved_by`";
        }
        
        if (!empty($additions)) {
            $sql .= implode(", ", $additions);
            
            echo "Running SQL:\n";
            echo $sql . "\n\n";
            
            if ($mysqli->query($sql)) {
                echo "✓ Successfully added columns!\n";
                echo "\nAdded columns:\n";
                foreach ($additions as $add) {
                    preg_match('/`([^`]+)`/', $add, $matches);
                    if (!empty($matches[1])) {
                        echo "  - " . $matches[1] . "\n";
                    }
                }
            } else {
                echo "✗ Error: " . $mysqli->error . "\n";
            }
        } else {
            echo "✓ All columns already exist!\n";
        }
    }
    
    echo "</pre>";
    echo "<p><a href='" . (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/accountant/lab-test-approvals') . "'>Go Back</a></p>";
    
    $mysqli->close();
    
} catch (Exception $e) {
    echo "<pre>Error: " . $e->getMessage() . "</pre>";
}
?>

