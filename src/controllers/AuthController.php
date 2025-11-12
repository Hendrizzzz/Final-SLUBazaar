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

    public function showLandingPage(): void
    {
        // Check for success or error flash messages
        $successMessage = $_SESSION['flash_success'] ?? null;
        $errorMessage = $_SESSION['flash_error'] ?? null;
        
        // Clear them so they don't show again
        unset($_SESSION['flash_success'], $_SESSION['flash_error']);
        
        // Load the view, making the messages available
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
        // 1. Get data from the form
        $emailOrUsername = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        
        // 2. Find the user in the database
        $userModel = new User($this->db);
        $user = $userModel->findByEmailOrUsername($emailOrUsername);

        // 3. Verify the user and password
        // The password_verify() function securely checks the submitted password against the stored hash.
        if ($user && password_verify($password, $user['password_hash'])) {
            
            // 4. SUCCESS! Store user's ID in the session to log them in.
            // This is the most important step for "remembering" the user.
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];

            // 5. Redirect to the marketplace
            header('Location: /market');
            exit();

        } else {
            
            // 6. FAILURE! Set an error message and redirect back to the login page.
            $_SESSION['flash_error'] = 'Invalid email/username or password.';
            header('Location: /');
            exit();
        }
    }


    /**
     * Handles the POST request from the registration form.
     */
    public function handleRegister(): void
    {
        $firstName = $_POST['first_name'] ?? '';
        $username = $_POST['username'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $passwordHash = password_hash($password, PASSWORD_BCRYPT);

        $userModel = new User($this->db);
        $result = $userModel->create($firstName, $username, $email, $passwordHash);
        
        if ($result) {
            // SUCCESS! Set a success flash message in the session.
            $_SESSION['flash_success'] = "Registration successful! You may now log in.";
            
            // Redirect back to the landing/login page
            header('Location: /');
            exit();
        } else {
            // You should also set an error flash message for failures
            // $_SESSION['flash_error'] = "Registration failed. Username or email may already be in use.";
            // For now, echoing an error is fine.
            echo "Error: Registration failed.";
        }
    }



    public function showLogin(): void
    {
        require __DIR__ . '/../views/user/user-landing-page.php'; // the same as landing page
    }
    
    
   
}