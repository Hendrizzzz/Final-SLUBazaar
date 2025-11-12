<?php

namespace Models;

use Core\Database;

class User
{
    /**
     * The database connection object.
     * @var Database
     */
    protected Database $db; // This property holds the connection

    /**
     * The constructor accepts the Database object and saves it.
     *
     * @param Database $db The database connection instance.
     */
    public function __construct(Database $db)
    {
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
        // This will now work because $this->db is a valid object
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

    
    /**
     * Finds a single user by their email or username.
     *
     * @param string $emailOrUsername
     * @return array|false The user's data as an associative array, or false if not found.
     */
    public function findByEmailOrUsername(string $emailOrUsername)
    {
        $sql = "SELECT * FROM user WHERE email = :identifier OR username = :identifier";
        
        $statement = $this->db->query($sql, ['identifier' => $emailOrUsername]);
        
        return $statement->fetch();
    }



    
    /**
     * Finds a single user by their ID.
     * @param int $userId
     * @return array|false
     */
    public function findById(int $userId)
    {
        $sql = "SELECT user_id, username, first_name FROM user WHERE user_id = :user_id";
        $statement = $this->db->query($sql, ['user_id' => $userId]);
        return $statement->fetch();
    }

    

}