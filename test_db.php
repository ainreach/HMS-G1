<?php
// Database configuration
$host = 'localhost';
$dbname = 'hms';
$username = 'root';
$password = '';

try {
    // Create connection
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Successfully connected to database: $dbname<br>";
    
    // List all tables in the database
    $tables = $conn->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    
    if (count($tables) > 0) {
        echo "<br>Tables in database:<br>";
        foreach ($tables as $table) {
            echo "- $table<br>";
        }
    } else {
        echo "<br>No tables found in the database. The database might be empty.<br>";
        echo "You'll need to import the database schema.";
    }
    
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
