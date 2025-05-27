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
    
<?php $_SESSION['username'] = null; ?>

<div class="container">
    <div class="form-container">
        <!-- Login Form -->
        <form id="login-form" class="form active" action="/gallarycafe/staff/login/login.php" method="POST">
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
           
        </form>

        <!-- Signup Form -->
        
    </div>
</div>



    
</body>
</html>
