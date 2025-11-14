<?php

use PHPUnit\Framework\TestCase;
use Core\Database;

class SimpleTest extends TestCase
{
    public function testDatabaseConnection()
    {
        $config = require __DIR__ . '/../config/database.php';
        $db = new Database($config);
        
        $this->assertNotNull($db, "Database object should be created");
        $this->assertInstanceOf('PDO', $db->connection, "Database connection should be a PDO instance");
    }
    
    public function testBasicAssertion()
    {
        $this->assertTrue(true, "Basic assertion should pass");
    }
}
?>