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

$row = $settingsCont->getServerData();

# Define variables and initialize with empty values
$domain_err = $files_type_err ="";

# Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    # Validate password
    if (empty(trim($_POST["domain"]))) {
        $domain_err = "Please enter a domain name.";
    }

    # Validate Files type
    if (empty(trim($_POST["files_type"]))) {
        $files_type_err = "Please atleast insert one file type.";
    }

    # Check input errors before inserting data into database
    if (empty($domain_err) && empty($file_size_err)) {

        #insert into server settings domain name
        $settingsCont->updateDomainName($_POST["domain"]);
        $settingsCont->updateFilesType($_POST["files_type"]);
    }

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Server Settings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="shortcut icon" href="../img/favicon-16x16.png" type="image/x-icon">
</head>

<body>
    <?php include './navbar.php'; ?>

    <div class="container">
        <div class="row pt-xxl-5 justify-content-center align-items-center">
            <div class="col-lg-5">
                <div class="form-wrap border rounded p-4 bg-special text-white">
                    <h1>Server Settings</h1>

                    <!-- form starts here -->
                    <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" novalidate>
                        <div class="mb-3">
                            <label for="username" class="form-label">Domain</label>
                            <input type="text" class="form-control bg-dark text-white" placeholder="enter domain name" name="domain" id="domain" value = <?php  echo $row['domain_name'] ;?>>
                            <small class="text-danger">
                                <?= $domain_err; ?>
                            </small>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Allowed files type</label>
                            <textarea  type="text-area" class="form-control bg-dark text-white" placeholder="exmp: txt, php ,ext" name="files_type" id="files_type" ><?php  echo $row['files_type'] ;?></textarea>
                            <small class="text-danger">
                                <?= $files_type_err; ?>
                            </small>
                        </div>

                            <div class="mb-3">
                                <input type="submit" class="btn btn-secondary form-control" name="submit" value="Edit Server Settings">
                            </div>
                    </form>

                    <!-- form ends here -->
                </div>
            </div>
        </div>
    </div>


    <!-- js includes -->
    <script src="https://kit.fontawesome.com/2a7eb584b0.js" crossorigin="anonymous"></script>

    <script defer src="../js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
</body>

</html>