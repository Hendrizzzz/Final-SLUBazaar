<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to SLU Bazaar</title>
    <link rel="stylesheet" href="/assets/css/user/user-landing-page.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="landing-wrapper">
        <div class="background-showcase">
            <div class="color-overlay"></div>
        </div>

        <main class="content-container">
            <header class="showcase-header">
                <h1 class="logo">Logo</h1>
                <p class="tagline">The official marketplace of Saint Louis University.</p>
            </header>

            <section class="login-container">
                <div class="login-box">
                    <h2>Log in</h2>

                    <?php if (isset($errorMessage)): ?>
                        <div class="alert alert-error">
                            <?= htmlspecialchars($errorMessage) ?>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($successMessage)): ?>
                        <div class="alert alert-success">
                            <?= htmlspecialchars($successMessage) ?>
                        </div>
                    <?php endif; ?>
                    
                    <form action="/login" method="POST" id="loginForm">
                        <div class="input-group">
                            <input type="text" id="email" name="email" required placeholder="Email or username">
                        </div>
                        <div class="input-group">
                            <input type="password" id="password" name="password" required placeholder="Password">
                        </div>
                        
                        <button type="submit" class="btn-primary">Continue</button>
                    </form>

                    <div class="divider">
                        <span>or</span>
                    </div>

                    <button class="btn-google">
                        <img src="https://www.google.com/favicon.ico" alt="Google icon">
                        Continue with Google
                    </button>
                    
                    <div class="options">
                        <label>
                            <input type="checkbox" name="stay_signed_in"> Stay signed in
                        </label>
                    </div>
                     <p class="register-link">
                        Don't have an account? <a href="/register">Sign up</a>
                    </p>
                </div>
            </section>
        </main>
    </div>
    
    <script src="/assets/js/user/user-landing-page.js"></script>
</body>
</html>