<?php
# Initialize the session
ob_start();
session_start();

# If user is not logged in then redirect him to login page 
#and if he's not adming he won't be able to reach this page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== TRUE) {
    echo "<script>" . "window.location.href='./pages/login.php';" . "</script>";
    exit;
  } elseif (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == TRUE && $_SESSION["id"] !== '1') {
    echo "<script>" . "window.location.href='./files.php'" . "</script>";
    exit;
  }

# Include connection
require_once "../database/config.php";
require_once "../database/functions.php";
# Define variables and initialize with empty values
$username_err = $email_err = $password_err = $file_size_err = $file_limit_err = "";
$username = $email = $password = $file_size = "";
$file_limit = 0;

# Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    # Validate username
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter a username.";
    } else {
        $username = trim($_POST["username"]);
        if (!ctype_alnum(str_replace(array("@", "-", "_"), "", $username))) {
            $username_err = "Username can only contain letters, numbers and symbols like '@', '_', or '-'.";
        } else {
            # Prepare a select statement
            $sql = "SELECT id FROM users WHERE username = ?";

            if ($stmt = mysqli_prepare($link, $sql)) {
                # Bind variables to the statement as parameters
                mysqli_stmt_bind_param($stmt, "s", $param_username);

                # Set parameters
                $param_username = $username;

                # Execute the prepared statement 
                if (mysqli_stmt_execute($stmt)) {
                    # Store result
                    mysqli_stmt_store_result($stmt);

                    # Check if username is already registered
                    if (mysqli_stmt_num_rows($stmt) == 1) {
                        $username_err = "This username is already registered.";
                    }
                } else {
                    echo "<script>" . "alert('Oops! Something went wrong. Please try again later.')" . "</script>";
                }

                # Close statement 
                mysqli_stmt_close($stmt);
            }
        }
    }

    # Validate email 
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter an email address";
    } else {
        $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $email_err = "Please enter a valid email address.";
        } else {
            # Prepare a select statement
            $sql = "SELECT id FROM users WHERE email = ?";

            if ($stmt = mysqli_prepare($link, $sql)) {
                # Bind variables to the statement as parameters
                mysqli_stmt_bind_param($stmt, "s", $param_email);

                # Set parameters
                $param_email = $email;

                # Execute the prepared statement 
                if (mysqli_stmt_execute($stmt)) {
                    # Store result
                    mysqli_stmt_store_result($stmt);

                    # Check if email is already registered
                    if (mysqli_stmt_num_rows($stmt) == 1) {
                        $email_err = "This email is already registered.";
                    }
                } else {
                    echo "<script>" . "alert('Oops! Something went wrong. Please try again later.');" . "</script>";
                }

                # Close statement
                mysqli_stmt_close($stmt);
            }
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

    # Validate File Size
    if (empty(trim($_POST["file_size"]))) {
        $file_size_err = "Please chose a file size.";
    } else {
        $file_size = trim($_POST["file_size"]);
        if (!is_numeric($file_limit)) {
            $file_size_err = "Please Enter a valid file size.";
        }
    }

    # Validate File limit
    if (empty(trim($_POST["file_limit"]))) {
        $file_limit_err = "Please chose a file limit.";
    } else {
        $file_limit = trim($_POST["file_limit"]);
        if (!is_numeric($file_limit)) {
            $file_limit_err = "Please Enter a valid file limit.";
        }
    }

    # Check input errors before inserting data into database
    if (empty($username_err) && empty($email_err) && empty($password_err) && empty($file_limit_err) && empty($file_size_err)) {
        $reg->insertintoUser($_POST);
    }

    # Close connection
    mysqli_close($link);
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
                    <h1>Sign up</h1>

                    <!-- form starts here -->
                    <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" novalidate>
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" name="username" id="username">
                            <small class="text-danger">
                                <?= $username_err; ?>
                            </small>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" name="email" id="email">
                            <small class="text-danger">
                                <?= $email_err; ?>
                            </small>
                        </div>
                        <div class="mb-2">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" name="password" id="password">
                            <small class="text-danger">
                                <?= $password_err; ?>
                            </small>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="togglePassword">
                            <label for="togglePassword" class="form-check-label">Show Password</label>
                        </div>
                        <div class="row">
                            <div class="col mb-3">
                                <label for="username" class="form-label">File Size (mb) </label>
                                <input type="number" class="form-control" name="file_limit" id="username">
                                <small class="text-danger">
                                    <?=  $file_limit_err; ?>
                                </small>
                            </div>
                            <div class="col mb-3">
                                <label for="username" class="form-label">File Limit (mb)</label>
                                <input type="number" class="form-control" name="file_size" id="username">
                                <small class="text-danger">
                                    <?= $file_size_err; ?>
                                </small>
                            </div>
                        </div>
                        <div class="mb-3">
                            <input type="submit" class="btn btn-primary form-control" name="submit" value="Add User">
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