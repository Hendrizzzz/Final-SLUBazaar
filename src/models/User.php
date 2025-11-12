<?php

namespace Models;

use Core\Database;

class User
{
    /**
     * The database connection object.
     * @var Database
     */
    protected Database $db; // Make sure this property is defined

    /**
     * THIS IS THE MISSING/INCORRECT PART.
     * The constructor accepts a Database object (dependency injection).
     *
     * @param Database $db The database connection instance.
     */
    public function __construct(Database $db)
    {
        // This line saves the database connection to the model's property
        $this->db = $db;
    }

    /**
     * Creates a new user in the database.
     *
     * @param string $firstName
     * @param string $username
     * @param string $email
     * @param string $passwordHash
     * @return bool True on success, false on failure
     */
    public function create(string $firstName, string $username, string $email, string $passwordHash): bool
    {
        // This line (line 23) will now work because $this->db is a valid object
        try {
            $sql = "INSERT INTO user (first_name, username, email, password_hash) VALUES (:first_name, :username, :email, :password_hash)";
            
            $this->db->query($sql, [
                'first_name' => $firstName,
                'username' => $username,
                'email' => $email,
                'password_hash' => $passwordHash
            ]);

            return true;
        } catch (\PDOException $e) {
            // Handle potential errors like a duplicate username/email
            error_log($e->getMessage()); // It's a good practice to log errors
            return false;
        }
    }
    
    // You will add other methods like find(), all(), etc. here later.
}