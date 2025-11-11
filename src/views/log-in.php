<?php

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SLU Bazaar | Login</title>
    <link rel="stylesheet" href="./assets/style.css">
    <link rel="stylesheet" href="global.css">
    <script src="./assets/main.js" defer></script>
</head>
<body>
    <div class="bg"></div>
    <div class="bg-overlay"></div>


    <div class="title-tagline-box">
        <div class="title-tagline">
        <img src="assets/img/SLU Logo.png" alt="">
        <div>
            <h1>SLU Bazaar</h1>
            <p>The official marketplace of Saint Louis University </p>
        </div>
    </div>
    </div>

    <div class="login-box">
        <div class="login-form">
        <h1>Log In</h1>

        <form method="POST">
            <div class="form-box">
                <div><input required type="text" name="email" id="email-input" placeholder="Email"></div>
                <div><input required type="password" name="password" id="password-input" placeholder="Password"></div>
                <div><button type="submit" class="continue">Continue</button></div>
                <p>or</p>
                <button type="button" class="google">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/3/3c/Google_Favicon_2025.svg" alt="Google icon">    
                    Continue with Google
                </button>
            </div>
        </form>
    </div>
    </div>
</body>
</html>
