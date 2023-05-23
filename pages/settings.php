<?php
# Initialize the session
session_start();

# If user is not logged in then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== TRUE) {
    echo "<script>" . "window.location.href='./login.php';" . "</script>";
    exit;
}

# Include connection
require_once "../database/config.php";
require_once "../database/functions.php";

# Define variables and initialize with empty values
$password_err = $oldPassword_err = "";
$password = "";
$userid = $_SESSION["id"];

# Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {


    # oldValidate password
    if (empty(trim($_POST["oldPassword"]))) {
        $oldPassword_err = "Please enter the Old password.";

    } elseif ($reg->chackPassword($_SESSION["id"], $_POST["oldPassword"])) {
        $oldPassword_err = "Old Password is Wrong.";

    } else {
        $oldpassword = trim($_POST["oldPassword"]);
        if (strlen($oldpassword) < 8) {
            $password_err = "Password must contain at least 8 or more characters.";
        }
    }
    # Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
    } else {
        $password = trim($_POST["password"]);
        if (strlen($password) < 8) {
            $password_err = "Password must contain at least 8 or more characters.";
        }
    }


    # Check input errors before inserting data into database
    if (empty($password_err) && empty($oldPassword_err)) {
        #call function update user password
        $update = $reg->updateUserPassword($userid, password_hash($password, PASSWORD_DEFAULT));
        if ($update) {
            echo "<script>" . "window.location.href='./files.php';" . "</script>";
            exit;
        } else {
            echo "<script>" . "alert('Oops! Something went wrong. Please try again later.');" . "</script>";
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
    <title>User login system</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="shortcut icon" href="../img/favicon-16x16.png" type="image/x-icon">
</head>

<body>
    <?php include './navbar.php'; ?>

    <div class="container">
        <div class="row pt-xxl-5 justify-content-center align-items-center">
            <div class="col-lg-5">
                <div class="form-wrap border rounded p-4 bg-white">
                    <h1>Update my profile</h1>
                    <!-- form starts here -->
                    <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" novalidate>
                        <div class="mb-3">
                            <!-- display user name -->
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" name="username" id="username" readonly value="<?= htmlspecialchars($_SESSION["username"]); ?>">
                        </div>
                        <!-- display email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" name="email" id="email" readonly value="<?= htmlspecialchars($_SESSION["email"]); ?>">
                        </div>

                        <div class="mb-2">
                            <label for="password" class="form-label"> Enter Old Password</label>
                            <input type="password" class="form-control" name="oldPassword" id="password">
                            <small class="text-danger">
                                <?= $oldPassword_err; ?>
                            </small>
                        </div>
                        <div class="mb-2">
                            <label for="password" class="form-label">Update Password</label>
                            <input type="password" class="form-control" name="password" id="passwordnew">
                            <small class="text-danger">
                                <?= $password_err; ?>
                            </small>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="togglePassword">
                            <label for="togglePassword" class="form-check-label">Show Password</label>
                        </div>
                        <div class="mb-3">
                            <input type="submit" class="btn btn-primary form-control" name="submit" value="Update">
                        </div>
                    </form>
                    <!-- form ends here -->
                </div>
            </div>
        </div>
    </div>




    <!-- js includes -->
    <script defer src="../js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
</body>

</html>