<?php

namespace Core; 

use PDO;
use PDOException;
use PDOStatement;

class Database
{
    /**
     * The PDO connection object. This property will hold the live database connection.
     * The `PDO` type-hint ensures it can only be a PDO object.
     * @var PDO
     */
    public PDO $connection;



    /**
     * The constructor is called when we create a new Database object.
     * It reads the configuration array and establishes the connection.
     *
     * @param array $config The database settings array from config/database.php
     */
    public function __construct(array $config)
    {
        // "Data Source Name" or DSN. This string tells PDO which driver to use and how to connect.
        $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['dbname']};charset={$config['charset']}";
        
        // Connection options for PDO.
        $options = [
            // VERY IMPORTANT: Makes PDO throw exceptions on error, which we can catch.
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            // By default, fetch results as associative arrays (['key' => 'value']). Much cleaner than indexed arrays.
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];

        try {
            // This is the action: create a new PDO instance to connect to the database.
            $this->connection = new PDO($dsn, $config['user'], $config['password'], $options);
        } catch (PDOException $e) {
            // If the connection fails for any reason, stop the application and show an error.
            // In a real production app, you would log this error instead of displaying it.
            throw new PDOException("Database connection failed: " . $e->getMessage(), (int)$e->getCode());
        }
    }



    /**
     * A secure method to prepare and execute a SQL query.
     * Using prepared statements is the ONLY way to prevent SQL injection.
     *
     * @param string $sql The SQL query string with named placeholders (e.g., "WHERE id = :id").
     * @param array $params An associative array of parameters to bind (e.g., ['id' => 123]).
     * @return PDOStatement The prepared statement object, ready to be fetched from.
     */
    public function query(string $sql, array $params = []): PDOStatement
    {
        // 1. Prepare the SQL statement to prevent injection.
        $statement = $this->connection->prepare($sql);
        
        // 2. Execute the statement, passing in the parameters to safely bind them.
        $statement->execute($params);
        
        // 3. Return the statement object so the calling Model can fetch the results.
        return $statement;
    }



    
}