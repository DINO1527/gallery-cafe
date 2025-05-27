<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Signup Page</title>
    <!-- Link to CSS -->
    <link rel="stylesheet" href="/gallarycafe/css/styles_login.css">
    <!-- Google Fonts (Optional for better typography) -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>
    
<?php $_SESSION['customer_id'] = null; ?>

<div class="container">
    <div class="form-container">
        <!-- Login Form -->
        <form id="login-form" class="form active" action="/gallarycafe/php/login/login.php" method="POST">
            <h2>Login</h2>
            <div class="input-group">
                <input type="text" id="login-username" name="username" required>
                <label for="login-username">Username</label>
            </div>
            <div class="input-group">
                <input type="password" id="login-password" name="password" required>
                <label for="login-password">Password</label>
            </div>
            <button type="submit" class="btn">Login</button>
            <p>Don't have an account? <a href="#" id="show-signup">Sign Up</a></p>
        </form>

        <!-- Signup Form -->
        <form id="signup-form" class="form" action="/gallarycafe/php/login/signup.php" method="POST">
            <h2>Sign Up</h2>
            <div class="input-group">
                <input type="text" id="signup-username" name="username" required>
                <label for="signup-username">Username</label>
            </div>
            <div class="input-group">
                <input type="email" id="signup-email" name="email" required>
                <label for="signup-email">Email</label>
            </div>
            <div class="input-group">
                <input type="tel" id="signup-phone" name="phone" required>
                <label for="signup-phone">Phone Number</label>
            </div>
            <div class="input-group">
                <input type="password" id="signup-password" name="password" required>
                <label for="signup-password">Password</label>
            </div>
            <div class="input-group">
                <input type="password" id="signup-confirm-password" name="confirm_password" required>
                <label for="signup-confirm-password">Confirm Password</label>
            </div>
            <button type="submit" class="btn">Sign Up</button>
            <p>Already have an account? <a href="#" id="show-login">Login</a></p>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const showSignup = document.getElementById('show-signup');
    const showLogin = document.getElementById('show-login');
    const formContainer = document.querySelector('.form-container');

    // Show Signup Form
    showSignup.addEventListener('click', (e) => {
        e.preventDefault();
        formContainer.classList.add('active');
    });

    // Show Login Form
    showLogin.addEventListener('click', (e) => {
        e.preventDefault();
        formContainer.classList.remove('active');
    });
});
</script>

    <!-- Link to JavaScript -->
    <script src="script.js"></script>
</body>
</html>
