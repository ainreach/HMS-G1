<?php
// Database configuration
$host = 'localhost';
$dbname = 'hms';
$username = 'root';
$password = '';

try {
    // Create connection
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Successfully connected to database: $dbname<br><br>";
    
    // List all tables
    $tables = $conn->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    
    if (count($tables) > 0) {
        echo "<strong>Tables in database:</strong><br>";
        foreach ($tables as $table) {
            echo "- $table<br>";
        }
    } else {
        echo "❌ No tables found in the database.<br>";
        echo "You'll need to import the database schema from hms.sql<br>";
    }
    
} catch(PDOException $e) {
    echo "❌ Connection failed: " . $e->getMessage() . "<br>";
    
    // Try to create the database if it doesn't exist
    if ($e->getCode() == 1049) { // Database doesn't exist
        try {
            $conn = new PDO("mysql:host=$host", $username, $password);
            $conn->exec("CREATE DATABASE IF NOT EXISTS `$dbname`");
            echo "✅ Created database: $dbname<br>";
            echo "Please import the hms.sql file now.<br>";
        } catch(PDOException $ex) {
            die("❌ Could not create database: " . $ex->getMessage());
        }
    }
}
?>
