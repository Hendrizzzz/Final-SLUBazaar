<?php

namespace Controllers;

use Core\Database;
use Models\User;

class AuthController
{
    protected $db;
    
    public function __construct(Database $db) {
        $this->db = $db;
    }

    /**
     * Shows the main landing/login page.
     * This is the new entry point for unauthenticated users.
     */
    public function showLandingPage(): void
    {
        // Simply load the view. No data is needed from the database for this page.
        require __DIR__ . '/../views/user/user-landing-page.php';
    }

    /**
     * Shows the registration page.
     */
    public function showRegister(): void
    {
        require __DIR__ . '/../views/user/user-register.php'; 
    }

    /**
     * Handles the POST request from the login form.
     */
    public function handleLogin(): void
    {
        // Your existing login logic will go here.
        // On success, redirect to the market:
        // header('Location: /market');
        // exit();
    }


    /**
     * Handles the POST request from the registration form.
     */
    public function handleRegister(): void
    {
        // 1. Get the form data
        $firstName = $_POST['first_name'] ?? '';
        $username = $_POST['username'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        // 2. TODO: Server-side validation (e.g., check if email already exists)
        
        // 3. Hash the password for security. NEVER store plain text passwords.
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);

        // 4. Ask the User model to create the user in the database
        $userModel = new User($this->db);
        $result = $userModel->create($firstName, $username, $email, $passwordHash);
        
        // 5. Redirect the user
        if ($result) {
            // Redirect to the login page with a success message (you can add this later)
            header('Location: /');
            exit();
        } else {
            // Handle registration failure (e.g., show an error message)
            echo "Error: Registration failed. The username or email might already be taken.";
        }
    }



    public function showLogin(): void
    {
        require __DIR__ . '/../views/user/user-landing-page.php'; // the same as landing page
    }
    
    
   
}