<?php
# Initialize the session
session_start();

# If user is not logged in then redirect him to login page 
#and if he's not adming he won't be able to reach this page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== TRUE) {
    echo "<script>" . "window.location.href='./login.php';" . "</script>";
    exit;
  } elseif (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == TRUE && $_SESSION["id"] !== '1') {
    echo "<script>" . "window.location.href='./files.php'" . "</script>";
    exit;
  }

# Include connection

require_once "../database/config.php";
require_once "../database/functions.php";

#file delete
if (isset($_POST['delete-file-submit'])) {
    $deletedfile = $fileCont->deleteFile($_POST['file_id'], $_POST['file_name'], $_POST['folder_name']);
}

#files data 
$files_result = $fileCont->getFilesData();

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
    <script src="https://kit.fontawesome.com/2a7eb584b0.js" crossorigin="anonymous"></script>
</head>

<body>

    <?php include './navbar.php'; ?>

    <!-- users table -->
    <div class="container">
        <div class=" py-5">
                <div class="card">
                    <div class="card-header fs-5">All Files</div>
                    <div class="px-2">
                        <table class="table ">
                            <thead>
                                <tr>
                                    <th scope="col">File ID</th>
                                    <th scope="col">Uploader Name</th>
                                    <th scope="col">File Name</th>
                                    <th scope="col">File Location</th>
                                    <th scope="col">File Type</th>
                                    <th scope="col">Upload Date</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                while ($row  = mysqli_fetch_assoc($files_result)) {
                                ?>
                                    <tr>
                                        <td>
                                            <?php echo $row['id'] ?>
                                        </td>
                                        <td>
                                            <?php echo $row['username'] ?>
                                        </td>
                                        <td>
                                            <?php echo $row['file_name'] ?>
                                        </td>
                                        <td>
                                            <?php echo $row['file_loc'] ?>
                                        </td>
                                        <td>
                                            <?php echo $row['file_type'] ?>
                                        </td>
                                        <td>
                                            <?php echo $row['created_at'] ?>
                                        </td>
                                        <th scope="col">
                                            <div class="row">
                                                <div class="col-5">
                                                    <form method="post">
                                                        <!-- hidden inputs for the delete -->
                                                        <input type="hidden" value="<?php echo $row['id']; ?>" name="file_id">
                                                        <input type="hidden" value="<?php echo $row['file_name']; ?>" name="file_name">
                                                        <input type="hidden" value="<?php echo $row['folder_name'];; ?>" name="folder_name">
                                                        <button type="submit" name="delete-file-submit" class="btn btn-danger btn-sm">Delete</button>
                                                    </form>
                                                </div>
                                                <div class="col-5">
                                                    <a href="<?php echo 'folders/'.$row['file_loc']; ?>" class="btn btn-primary btn-sm" Download="<?php echo $row['file_name'] ?>"> Download</a>
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

    <!-- users table -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
</body>

</html>