<?php
# Initialize session
session_start();
require_once "../database/functions.php";


# Check if user is already logged in, If yes then redirect him to index page
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == TRUE) {
  echo "<script>" . "window.location.href='./users.php'" . "</script>";
  exit;
}

# Define variables and initialize with empty values
$user_login_err = $user_password_err = $err ="";


if ($_SERVER['REQUEST_METHOD'] == "GET") {
  if (isset($_GET['Invalid'])) {
      $err = $_GET['Invalid'];
  }
} 


# Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (empty(trim($_POST["username"]))) {
    $user_login_err = "Please enter your username.";
  }

  if (empty(trim($_POST["password"]))) {
    $user_password_err = "Please enter your password.";

  }

  # Validate credentials 
  if (empty($user_login_err) && empty($user_password_err)) {

    if (isset($_POST['login-submit'])) {
      //CALL method identification to login 
      $reg->identification($_POST);
  }

  }

}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Log In</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
  <link rel="stylesheet" href="../css/main.css">
  <link rel="shortcut icon" href="./img/favicon-16x16.png" type="image/x-icon">
  <script defer src="../js/script.js"></script>
</head>

<body>
  <div class="container">
    <div class="row min-vh-100 justify-content-center align-items-center">
      <div class="col-lg-5">
        <?php
        if (!empty($err)) {
          echo "<div class='alert alert-danger'>" . $err . "</div>";
        }
        ?>
        <div class="form-wrap border rounded p-4  bg-special text-white">
          <h1>Log In</h1>
          <!-- form starts here -->
          <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" novalidate>
            <div class="mb-3">
              <label for="user_login" class="form-label">Username</label>
              <input type="text" class="form-control bg-dark text-white" name="username" id="user_login">
              <small class="text-danger"><?= $user_login_err; ?></small>
            </div>
            <div class="mb-2">
              <label for="password" class="form-label">Password</label>
              <input type="password" class="form-control bg-dark text-white" name="password" id="password">
              <small class="text-danger"><?= $user_password_err; ?></small>
            </div>
            <div class="mb-3 form-check">
              <input type="checkbox" class="form-check-input" id="togglePassword">
              <label for="togglePassword" class="form-check-label">Show Password</label>
            </div>
            <div class="mb-3">
              <input type="submit" class="btn btn-secondary form-control" name="login-submit" value="Log In">
            </div>
          </form>
          <!-- form ends here -->
        </div>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>

</body>

</html>