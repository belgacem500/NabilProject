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

if (isset($_POST['delete-file-submit'])) {
    $deletedfile = $fileCont->deleteFile($_POST['file_id'], $_POST['file_name'], $_POST['folder_name']);
}

#to Get the user data from the data base
$users_data = $reg->getUsersById($_SESSION['id']);

#select where uploader id = the user id

$files_result = $fileCont->filesDataById($_SESSION['id']);

#to count how many file the user already uploaded


$file_count = $fileCont->getFileCountById($_SESSION['id']);

# Define variables and initialize with empty values
$file_err = "";
$username = $uploader_id = $file_name = $file_loc = $file_type = "";
$file_limit = 0;

$check_type = $settingsCont->getFilesType();
$checkarray = explode(",", str_replace(' ', '', $check_type['files_type']));

# Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $file_name = $_FILES["upFile"]["name"];
    $target_dir = $users_data['folder_name'];
    $target_file = $target_dir . '/' . basename($_FILES["upFile"]["name"]);

    if (file_exists(UPLOAD_SERVER . '/' . $target_file)) {
        $file_err = "Sorry, file already exists.";
    }

    # Validate File limit
    if ($file_count['file_num'] >= $users_data["file_lim"]) {
        $file_err = "You have reached max file limit.";
    }

    # Validate File Size
    if ($_FILES["upFile"]["size"] > (intval($users_data["file_size"]) * 1000000)) {
        $file_err = "file size is out of your permition.";
    }
    if ($file_name == "") {
        $file_err = "Please add a valide file.";
    }

    $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // formats that's not allowed
    if (!in_array($file_type, $checkarray)) {
        $file_err = "Sorry, this file format are not allowed.";
    }


    # Check input errors before inserting data into database
    if (empty($file_err)) {
             # call functions add file
            $add_file= $fileCont->addfile($_SESSION["id"], $file_name, $target_file, $file_type , $_FILES["upFile"]["tmp_name"]);
            # Execute the prepared statement
    }

}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My files</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="shortcut icon" href="../img/favicon-16x16.png" type="image/x-icon">

</head>

<body>

    <?php include './navbar.php'; ?>

    <!-- users table -->
    <div class="container">
        <div class="row py-5">
            <div class="col-md-8">
                <div class="card bg-special text-white">
                    <div class="card-header fs-5 bg-special text-white">All Files</div>
                    <div class="px-2">
                        <table class="table bg-special text-white">
                            <thead>
                                <tr>
                                <?php if($_SESSION['id']==1){?>
                                    <th scope="col">File ID</th>
                                    <th scope="col">File Name</th>
                                    <th scope="col">File Type</th>
                                    <th scope="col">Upload Date</th>
                                    <?php } ?>
                                    <th scope="col">File Location</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                while ($row = mysqli_fetch_assoc($files_result)) {
                                ?>
                                    <tr>
                                    <?php if($_SESSION['id']==1){?>
                                        <td>
                                            <?php echo $row['id']; ?>
                                            </td>
                                        <td>
                                            <?php echo $row['file_name'] ?>
                                        </td>

                                        <td>
                                            <?php echo $row['file_type'] ?>
                                        </td>
                                        <td>
                                            <?php echo $row['created_at'] ?>
                                        </td>

                                        <?php }?>

                                        <td>
                                            <?php echo $row['file_loc'] ?>
                                        </td>
                                        <th scope="col">
                                            <div class="row">
                                                <div class="col-5">
                                                    <form method="post">
                                                        <!-- hidden inputs for the delete -->
                                                        <input type="hidden" value="<?php echo $row['id']; ?>" name="file_id">
                                                        <input type="hidden" value="<?php echo $row['file_name']; ?>" name="file_name">
                                                        <input type="hidden" value="<?php echo $users_data['folder_name'];; ?>" name="folder_name">
                                                        <button type="submit" name="delete-file-submit" class="btn btn-danger btn-sm"> <i class="fa-solid fa-trash-can me-1"></i> Delete</button>
                                                    </form>
                                                </div>
                                                <div class="col-6">
                                                    <a href="<?php echo 'folders/'.$row['file_loc']; ?>" class="btn btn-outline-dark bg-light btn-sm" Download="<?php echo $row['file_name'] ?>"> <i class="fa-solid fa-circle-down me-1"></i> Download</a>
                                        </th>
                    </div>
                    </tr>
                <?php
                                }
                ?>
                </tbody>
                </table>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-special text-white">
                <div class="card-header fs-5">Add File</div>
                <div class="card-body">
                    <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <input class="form-control bg-dark text-white" name="upFile" type="file" id="formFile">
                            <small class="text-danger">
                                <?= $file_err; ?>
                            </small>
                        </div>
                        <button type="submit" name="submit" class="btn btn-light bg-light mt-3"> <i class="fa-solid fa-circle-arrow-up me-1"></i> Upload file</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>

    <!-- users table -->
    <script src="https://kit.fontawesome.com/2a7eb584b0.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>

</body>

</html>