<?php
# Initialize the session
session_start();

require_once "../database/functions.php";
# check if the user exist
if (!$reg->checkUserExist($_SESSION['id'])) {
    session_destroy();
    echo "<script>window.location.href='./login.php';</script>";
    exit;
}

# If user is not logged in then redirect him to login page 
#and if he's not adming he won't be able to reach this page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== TRUE) {
    echo "<script>" . "window.location.href='./login.php';" . "</script>";
    exit;
} elseif (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == TRUE && $_SESSION["id"] !== '1') {
    echo "<script>" . "window.location.href='./files.php'" . "</script>";
    exit;
}


$num_per_page = 7;

if (isset($_GET["page"])) {
    $page= $_GET["page"];
} else {
    
    $page = 1;
}

$start_from = ($page - 1) * $num_per_page;
#files data 
$files_result = $fileCont->getFilesData($start_from, $num_per_page);

#file delete
if (isset($_POST['delete-file-submit'])) {
    $deletedfile = $fileCont->deleteFile($_POST['file_id'], $_POST['file_name'], $_POST['folder_name']);
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All files</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="shortcut icon" href="../img/favicon-16x16.png" type="image/x-icon">
</head>

<body>

    <?php include './navbar.php'; ?>

    <!-- users table -->
    <div class="container">
        <div class=" py-5">
            <div class="card bg-special text-white">
                <div class="card-header fs-5">All Files</div>
                <div class="px-2">
                    <table class="table bg-special text-white">
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
                                        <?php echo UPLOAD_SERVER.'/'.UPLOAD_FOLDER .'/'. $row['file_loc']; ?>
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
                                                    <button type="submit" name="delete-file-submit" class="btn btn-danger  btn-sm"><i class="fa-solid fa-trash-can"></i></button>
                                                </form>
                                            </div>
                                            <div class="col-5">
<!--                                             <button class="btn btn-secondary text-white btn-sm" data-link="<?php /* echo UPLOAD_SERVER.'/'.UPLOAD_FOLDER .'/'. $row['file_loc']; */ ?>" onclick="myFunction(this)"><i class="fa-solid fa-copy me-1"></i> copie</button> -->                                            </div>
                                    </th>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

            </div>
            <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-center">
                    <?php

                    $total_records = $fileCont->filesCount();

                    $total_pages = ceil($total_records / $num_per_page);

                    for ($i = 1; $i <= $total_pages; $i++) {
                        if($i == $page){
                            echo "<li class='page-item'><a href='allFiles.php?page=" . $i . "' class ='page-link active btn-bg-dark mt-5 '>" . $i . "</a></li>";
                        }else{
                            echo "<li class='page-item'><a href='allFiles.php?page=" . $i . "' class =' page-link  btn-bg-dark  mt-5 '>" . $i . "</a></li>";

                        }
                    }
                    ?>
                </ul>
            </nav>
        </div>
    </div>

    <!-- users table -->
    <script src="https://kit.fontawesome.com/2a7eb584b0.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <script src="../js/copyfile.js"></script>

</body>

</html>