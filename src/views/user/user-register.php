<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - SLU Bazaar</title>
    <!-- We are intentionally reusing the landing page CSS for a consistent theme -->
    <link rel="stylesheet" href="/assets/css/user/user-landing-page.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="landing-wrapper">
        <div class="background-showcase">
            <div class="color-overlay"></div>
        </div>

        <main class="content-container" style="justify-content: center;">
            <section class="login-container">
                <div class="login-box">
                    <h2>Create an Account</h2>
                    
                    <!-- This form's action points to /register with method="POST", matching your router -->
                    <form action="/register" method="POST" id="registerForm" novalidate>
                        <div class="input-group">
                            <input type="text" id="first_name" name="first_name" required placeholder="First Name">
                        </div>
                        <div class="input-group">
                            <input type="text" id="username" name="username" required placeholder="Username">
                        </div>
                        <div class="input-group">
                            <input type="email" id="email" name="email" required placeholder="Email Address">
                        </div>
                        <div class="input-group">
                            <input type="password" id="password" name="password" required placeholder="Password">
                        </div>
                        <div class="input-group">
                            <input type="password" id="password_confirm" name="password_confirm" required placeholder="Confirm Password">
                        </div>
                        
                        <button type="submit" class="btn-primary">Sign Up</button>
                    </form>

                    <p class="register-link" style="margin-top: 1rem;">
                        Already have an account? <a href="/">Log In</a>
                    </p>
                </div>
            </section>
        </main>
    </div>
    
    <!-- Link to our new JavaScript file -->
    <script src="/assets/js/user/user/register.js"></script>
</body>
</html>