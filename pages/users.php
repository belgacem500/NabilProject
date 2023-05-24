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
require_once "../database/config.php";
require_once "../database/functions.php";

if (isset($_POST['delete-user-submit'])) {
  $deleteduser = $reg->deleteUser($_POST['user_id']);
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
    <!-- users table -->
    <div class="py-5">
      <div class="card">
        <div class="card-header ">
          <div class="row">
            <div class="col fs-5">
              All Users
            </div>
            <div class="col adduser-margin">
              <a href="./addUser.php"><button type="button" class="btn btn-success text-white btn-sm "><i class="fa-solid fa-user-plus me-2"></i>Add User</button> </a>
            </div>
          </div>
        </div>
        <div class="px-2">
          <table class="table ">
            <thead>
              <tr>
                <th scope="col">User ID</th>
                <th scope="col">Name</th>
                <th scope="col">Email</th>
                <th scope="col">password</th>
                <th scope="col">File Size</th>
                <th scope="col">Files Limit</th>
                <th scope="col">Folder Name</th>
                <th scope="col">Registe Date</th>
                <th scope="col">action</th>
              </tr>
            </thead>
            <tbody>
              <?php
              foreach ($reg->getUsersData() as $row) :
                
              ?>
                <td><?php echo $row['id'] ?></td>
                <td><?php echo $row['username'] ?></td>
                <td><?php echo $row['email'] ?></td>
                <td><?php echo $row['password'] ?></td>
                <td><?php echo $row['file_size'] ?></td>
                <td><?php echo $row['file_lim'] ?></td>
                <td><?php echo $row['folder_name'] ?></td>
                <td><?php echo $row['reg_date'] ?></td>
                <th scope="col"><a href="./editUser.php">
                  <div class="row">
                    <div class="col-4">
                    <a href="./editUser.php?id=<?php echo $row['id']; ?>" class="btn btn-info btn-sm text-white"> <i class="fa-solid fa-pen me-2"></i>Edit</a>
                    </div>

                    <div class="col-4">
                    <form method="post">
                      <input type="hidden" value="<?php echo $row['id']; ?>" name="user_id">
                      <button type="submit" name="delete-user-submit" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash-can me-1"></i>Delete</button>
                    </form>
                    </div>



                    </div>
                    </th>

                </tr>
              <?php

              endforeach ?>
            </tbody>
          </table>

        </div>

      </div>
    </div>

  </div>
  <!-- users table -->

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
  <script src="https://kit.fontawesome.com/2a7eb584b0.js" crossorigin="anonymous"></script>
              
</body>

</html>