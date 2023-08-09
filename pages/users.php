<?php
session_start();
require_once "../database/functions.php";
# check if the user exist
if (!$reg->checkUserExist($_SESSION['id'])) {
    session_destroy();
    echo "<script>window.location.href='./login.php';</script>";
    exit;
}

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== TRUE) {
  echo "<script>window.location.href='./login.php';</script>";
  exit;
} elseif (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == TRUE && $_SESSION["id"] !== '1') {
  echo "<script>window.location.href='./files.php';</script>";
  exit;
}

require_once "../database/functions.php";

$num_per_page = 7;
$page = isset($_GET["page"]) ? $_GET["page"] : 1;
$start_from = ($page - 1) * $num_per_page;
$searchTerm = isset($_GET["search"]) ? $_GET["search"] : "";

if (isset($_POST['delete-user-submit'])) {
  $deleteduser = $reg->deleteUser($_POST['user_id']);
}

$users = $reg->getUsersDataWithSearch($searchTerm, $start_from, $num_per_page);
$total_records = $reg->usersCountWithSearch($searchTerm);
$total_pages = ceil($total_records / $num_per_page);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>All Users</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
  <link rel="stylesheet" href="../css/main.css">
  <link rel="icon" type="image/png" href="../img/icon.png">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
  <script src="https://kit.fontawesome.com/2a7eb584b0.js" crossorigin="anonymous"></script>
</head>

<body>
<?php include './navbar.php'; ?>

<div class="container">
  <!-- ... (existing HTML code) -->
</div>

<script>
var currentColumn = -1;
var sortDirection = 'asc';

// Function to sort the table by a given column
function sortTable(column) {
  var table = document.getElementById('userTable');
  var tbody = table.tBodies[0];
  var rows = Array.from(tbody.rows);

  rows.sort(function(a, b) {
    var valueA = a.cells[column].innerText || a.cells[column].textContent;
    var valueB = b.cells[column].innerText || b.cells[column].textContent;

    if (valueA <= valueB) {
      return sortDirection === 'asc' ? -1 : 1;
    } else if (valueA > valueB) {
      return sortDirection === 'asc' ? 1 : -1;
    } else {
      return 0;
    }
  });

  // Clear the table body
  while (tbody.firstChild) {
    tbody.removeChild(tbody.firstChild);
  }

  // Reorder the rows in the table
  for (var i = 0; i < rows.length; i++) {
    tbody.appendChild(rows[i]);
  }

  // Update the sorting direction and arrow icon
  var headers = document.querySelectorAll('#userTable th');
  currentColumn = column;
  sortDirection = sortDirection === 'asc' ? 'desc' : 'asc';
}

</script>
  <div class="container">
    <div class="py-5">
      <div class="card bg-special text-white">
        <div class="card-header">
          <div class="row">
            <div class="col-2 fs-5">All Users</div>
            <div class="col-4" style="margin-left: 10%;">
              <form method="get">
                <div class="input-group">
                  <input type="text" class="form-control bg-dark text-white" name="search" placeholder="Search by username" aria-label="Search by username" value="<?php echo $searchTerm; ?>">
                  <button class="btn btn-outline-secondary text-white btn-sm" type="submit">Search</button>
                </div>
              </form>
            </div>
            <div class="col-4 adduser-margin">
              <a href="./addUser.php"><button type="button" class="btn btn-success text-white btn-sm"><i class="fa-solid fa-user-plus me-2"></i>Add User</button></a>
            </div>
          </div>
        </div>
        <div class="px-2" id="searchresult">
          <table class="table text-white" id="userTable">
            <thead>
              <tr>
                <th scope="col" id="header-user-id" onclick="sortTable(0)" class="sortable">User ID <i class="fas fa-sort"></i></th>
                <th scope="col" id="header-name" onclick="sortTable(1)" class="sortable">Name <i class="fas fa-sort"></i></th>
                <th scope="col" id="header-email" onclick="sortTable(2)" class="sortable">Email <i class="fas fa-sort"></i></th>
                <th scope="col" id="header-file-size" class="sortable">File Size</th>
                <th scope="col" id="header-file-limit" class="sortable">Files Limit</i></th>
                <th scope="col" id="header-folder-name" onclick="sortTable(5)" class="sortable">Folder Name <i class="fas fa-sort"></i></th>
                <th scope="col" id="header-reg-date" onclick="sortTable(6)" class="sortable">Register Date <i class="fas fa-sort"></i></th>
                <th scope="col">Action</th>
              </tr>
            </thead>
            <tbody id="userTableBody">
              <?php
              foreach ($users as $row) :
              ?>
                <tr>
                  <td><?php echo $row['id'] ?></td>
                  <td><?php echo $row['username'] ?></td>
                  <td><?php echo $row['email'] ?></td>
                  <td><?php echo $row['file_size'] ?></td>
                  <td><?php echo $row['file_lim'] ?></td>
                  <td><?php echo $row['folder_name'] ?></td>
                  <td><?php echo $row['reg_date'] ?></td>
                  <th scope="col">
                    <a href="./editUser.php?id=<?php echo $row['id']; ?>" class="btn btn-secondary mb-1 btn-sm text-white"> <i class="fa-solid fa-pen me-2"></i>Edit</a>
                    <?php
                    if($row['username']!== 'admin'){
                    ?>
                    <form method="post">
                      <input type="hidden" value="<?php echo $row['id']; ?>" name="user_id">
                      <button type="submit" name="delete-user-submit" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash-can me-1"></i>Delete</button>
                    </form>
                    <?php
                    }
                    ?>
                  </th>
                </tr>
              <?php
              endforeach;
              ?>
            </tbody>
          </table>
        </div>
      </div>
      <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-center">
          <?php
          for ($i = 1; $i <= $total_pages; $i++) {
            if ($i == $page) {
              echo "<li class='page-item'><a href='users.php?page=" . $i . "&search=" . urlencode($searchTerm) . "' class ='page-link active btn-bg-dark mt-5 '>" . $i . "</a></li>";
            } else {
              echo "<li class='page-item'><a href='users.php?page=" . $i . "&search=" . urlencode($searchTerm) . "' class =' page-link  btn-bg-dark  mt-5 '>" . $i . "</a></li>";
            }
          }
          ?>
        </ul>
      </nav>
    </div>
  </div>

</body>

</html>