<?php
session_start();
require_once "config/db_con.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Toastr -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <!-- PAGE TITLE HERE -->
  <title>Login</title>

  <!-- FAVICONS ICON -->
  <link rel="shortcut icon" type="image/png" href="images/favicon.png">
  <link rel="stylesheet" href="css/style_login.css">

</head>

<body class="vh-100">
  <div class="notifications"></div>
  <div class="container">
    <div class="forms-container">

      <div class="signin-signup">
        <form method="POST" action="config/login_auth.php" class="sign-in-form" id="userLoginForm">
          <h2 class="title">Sign in</h2>

          <div class="input-field">
            <i class="fas fa-user"></i>
            <input type="text" placeholder="Email" name="email" required />
          </div>
          <div class="input-field">
            <i class="fas fa-lock"></i>
            <input type="password" placeholder="Password" name="password" required />
          </div>
          <button type="submit" class="btn solid" name="btn_login" id="sign-in-btn">Login</button>

          <?php if (isset($_SESSION['error'])) { ?>
            <div id="errorAlert" style=" background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; padding: 15px 40px 15px 15px; border-radius: 5px; margin-top: 20px; position: relative;">
            <strong>Error:</strong> <?= htmlspecialchars($_SESSION['error']); ?>
            <span onclick="document.getElementById('errorAlert').style.display='none';" 
                  style=" position: absolute; top: 50%; right: 15px; transform: translateY(-50%); color: #721c24; font-weight: bold; cursor: pointer; font-size: 20px;">&times;
            </span>
          </div>
          <script>
            // Automatically hide the alert after 3 seconds (3000 ms)
            setTimeout(function() {
              var alertBox = document.getElementById('errorAlert');
              if (alertBox) {
                alertBox.style.display = 'none';
              }
            }, 3000);
          </script>
        <?php } unset($_SESSION['error']); ?>

        </form>
      </div>
    </div>

    <div class="panels-container">
      <div class="panel left-panel">
        <div class="content">
          <h3 style="margin-left: 10px">DONATION TRACKER</h3>
          <p>
            XAVIER SCHOOL
          </p>
          <button class="btn transparent" id="sign-up-btn">
            Sign in here
          </button>
        </div>
        <img src="images/login-donation.png" class="image" alt="" />
      </div>
    </div>
  </div>

  <script>
    const sign_in_btn = document.querySelector("#sign-in-btn");
    const container = document.querySelector(".container");

    sign_in_btn.addEventListener("click", () => {
      container.classList.remove("sign-up-mode");
    });
  </script>


  <!--**********************************
        Scripts
    ***********************************-->
  <!-- Required vendors -->
  <script src="vendor/global/global.min.js"></script>

  <script src="js/custom.min.js"></script>
  <script src="js/dlabnav-init.js"></script>
</body>
<?php
session_destroy();
?>
</html>