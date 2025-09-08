<?php
require_once 'config/config.php';

$db = new Database();
$connection = $db->getConnection();

if ($connection) {
    echo "✅ Database connection successful!<br>";
    
    // Test tables exist
    $tables = ['contact_submissions', 'job_applications', 'settings'];
    foreach ($tables as $table) {
        $result = $connection->query("SHOW TABLES LIKE '$table'");
        if ($result->num_rows > 0) {
            echo "✅ Table '$table' exists<br>";
        } else {
            echo "❌ Table '$table' missing<br>";
        }
    }
    
    // Test settings
    $result = $connection->query("SELECT * FROM settings");
    if ($result) {
        echo "✅ Settings table has " . $result->num_rows . " entries<br>";
    }
    
} else {
    echo "❌ Database connection failed!";
}
?>