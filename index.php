<?php
    session_start();
    require_once "config/db.php";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>888 KAIROS Apparel Shop Login</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #0a6e56;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-form {
            background-color: #88c1b3;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .logo img {
            margin-top: -70px;
        }
        .login-form .btn-login {
            background-color: #d1a854;
            color: #fff;
            border: none;
            width: 3in;
        }
        .login-form .btn-login:hover {
            background-color: #c09949;
        }
        .times {
            font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
        }
        .form-group {
            position: relative;
        }
        .show-password {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="login-form">
        <div class="logo">
            <img src="images/logo.png" height="100px" width="100px">
        </div>
        <h1 class="h3 mb-3 font-weight-normal">888 KAIROS<br>Apparel Shop</h1>
        <form method="POST" action="config/login_authentication.php">
        <?php
 
    if (isset($_SESSION['error'])) {
        echo '<div class="alert alert-danger" role="alert">' . $_SESSION['error'] . '</div>';
        unset($_SESSION['error']); // Clear the error message after displaying it
    }
    ?>
        <div id="alertMessage" class="alert alert-danger d-none" role="alert"></div>
            <div class="form-group">
                <input type="text" class="form-control" placeholder="Username" name="username" required autofocus>
            </div>
            <div class="form-group">
                <input type="password" class="form-control" placeholder="Password" name="password" id="password" required>
                <span class="show-password" onclick="togglePassword()">
                    <i class="fas fa-eye" id="togglePasswordIcon"></i>
                </span>
            </div>
            <button class="btn btn-lg btn-login btn-block" type="submit">Login</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://use.fontawesome.com/releases/v5.15.1/js/all.js"></script>
    <script>
      

        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('togglePasswordIcon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
