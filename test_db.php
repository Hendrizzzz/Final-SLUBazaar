<?php
require 'vendor/autoload.php';

$config = require 'config/database.php';
print_r($config);

try {
    $db = new Core\Database($config);
    echo "Database connection successful!\n";
    
    // Test a simple query
    $stmt = $db->query("SELECT 1 as test");
    $result = $stmt->fetch();
    print_r($result);
} catch (Exception $e) {
    echo "Database connection failed: " . $e->getMessage() . "\n";
}
?>