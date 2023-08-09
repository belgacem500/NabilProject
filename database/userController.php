<?php

//php UsersServ calss
class userController
{
    public $db = null;

    public function __construct(DBController $db)
    {
        if (!isset($db->con))
            return null;
        $this->db = $db;
    }

    //function to create a unique folder id
    public function uniqueFolderId()
    {
        $result = 1;
        while ($result) {
            $number = uniqid();
            $varray = str_split($number);
            $len = sizeof($varray);
            $otp = array_slice($varray, $len - 6, $len);
            $otp = implode(",", $otp);
            $otp = str_replace(',', '', $otp);
            $result = $this->db->con->query("SELECT id FROM users WHERE folder_name ='{$otp}'");
            $result = mysqli_fetch_array($result, MYSQLI_ASSOC);
        }

        return $otp;
    }

    //insert into user table ( insert )

    public function insertintoUser($params = null, $table = "users")
    {

        if ($this->db->con != null) {
            if ($params != null) {
                //create sql query
                $folder_name = $this->uniqueFolderId();
                $query_string = sprintf("INSERT INTO %s(username, email, password, file_size, file_lim, folder_name) 
                VALUES('%s','%s','%s','%s','%d','%s')", $table, $params["username"], $params["email"], password_hash($params["password"], PASSWORD_BCRYPT), $params["file_limit"], $params["file_size"], $folder_name);
                mkdir($_SERVER['DOCUMENT_ROOT'] . '/'.UPLOAD_FOLDER . '/' . $folder_name);
                    // Create the index.php file inside the folder

                $indexFile = $_SERVER['DOCUMENT_ROOT'] . '/'.UPLOAD_FOLDER . '/' . $folder_name. '/index.php';
                $content = '<?php' . PHP_EOL . 'echo "404";' . PHP_EOL . '?>';
                file_put_contents($indexFile, $content);
                

                //execute query
                $result = $this->db->con->query($query_string);
                echo "<script>" . "window.location.href='./users.php';" . "</script>";
                return $result;
            }
        }
    }
    //login function
    public function identification($params = null, $table = 'users')
    {

        if ($this->db->con != null) {

            $query_string = sprintf("SELECT * FROM %s WHERE username = '%s'", $table, $params["username"]);

            if ($params != null) {
                $result = $this->db->con->query($query_string);
                $queryResult = mysqli_fetch_array($result, MYSQLI_ASSOC);
                $password = $queryResult['password'];

                if (password_verify($params['password'], $password)) {
                    //variables
                    $_SESSION['username'] = $queryResult['username'];
                    $_SESSION['email'] = $queryResult['email'];
                    $_SESSION['folder_name'] = $queryResult['folder_name'];
                    $_SESSION['file_lim'] = $queryResult['file_lim'];
                    $_SESSION['file_size'] = $queryResult['file_size'];
                    $_SESSION["loggedin"] = TRUE;
                    $_SESSION['id'] = $queryResult['id'];

                    //admin or normal user
                    if ($_SESSION['username'] == "admin") {
                        echo "<script>" . "window.location.href='./users.php'" . "</script>";
                    } else {
                        echo "<script>" . "window.location.href='./files.php'" . "</script>";
                    }
                    die;
                } else {
                    //wrong user name or password
                    header('Location: ./login.php?Invalid=Please enter Coerrect user name and password');
                    die;
                }
            }
        }
    }

    public function chackPassword($id = null, $password = null, $table = 'users')
    {
        if (isset($id) && isset($password)) {

            $result = $this->db->con->query("SELECT password FROM {$table} WHERE id  = '{$id}'");

            if ($result != false) {
                $realpassword = mysqli_fetch_array($result, MYSQLI_ASSOC);
                if (password_verify($password, $realpassword['password'])) {
                    return false;
                } else {
                    return true;
                }
            }
        }
    }
    //get all user data
    public function getUsersData($start_from = 0, $num_per_page = 0, $table = 'users')
    {

        $result = $this->db->con->query("SELECT * FROM {$table} limit {$start_from},{$num_per_page}");

        $resultArray = array();

        if ($result != false) {
            while ($item = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $resultArray[] = $item;
            }
        }

        return $resultArray;
    }

    //get specific user data using id
    public function getUsersById($user_id = null, $table = 'users')
    {
        if (isset($user_id)) {
            $result = $this->db->con->query("SELECT * FROM {$table} WHERE id = '{$user_id}'");

            if ($result != false) {
                $resultArray = mysqli_fetch_array($result, MYSQLI_ASSOC);
            }
        }

        return $resultArray;
    }

    //check if username is already used
    public function checkUserName($username = null, $table = 'users')
    {
        if (isset($username)) {
            $result = $this->db->con->query("SELECT id FROM {$table} WHERE username = '{$username}'");
            if ($result != false) {
                $resultArray = mysqli_fetch_array($result, MYSQLI_ASSOC);

                if (isset($resultArray)) {
                    $check = $resultArray['id'];
                } else {
                    $check = 0;
                }

                return $check;
            }
        }
    }

    //check if user email is already used
    public function checkUserEmail($email = null, $table = 'users')
    {
        if (isset($email)) {
            $result = $this->db->con->query("SELECT id FROM {$table} WHERE email = '{$email}'");
            if ($result != false) {
                $resultArray = mysqli_fetch_array($result, MYSQLI_ASSOC);

                if (isset($resultArray)) {
                    $check = $resultArray['id'];
                } else {
                    $check = 0;
                }

                return $check;
            }
        }
    }

    //delete user using id
    public function deleteUser($user_id = null, $table = 'users')
    {
        if ($user_id != null) {

            $result1 = $result = $this->db->con->query("SELECT folder_name FROM users WHERE id ={$user_id}");

            $row = mysqli_fetch_assoc($result1);
            
            $dirname = $_SERVER['DOCUMENT_ROOT'] . '/'.UPLOAD_FOLDER . '/' . $row["folder_name"];
            array_map('unlink', glob("$dirname/*.*"));
            rmdir($dirname);

            $result = $this->db->con->query("DELETE FROM {$table} WHERE id={$user_id}");

            if ($result) {
                header("Location:" . $_SERVER['PHP_SELF']);
            }
            return $result;
        }
    }

    //edit user information by admin
    public function updateUser($param_user_id = NULL, $paramusername = NULL, $param_email = NULL, $param_password = NULL, $param_file_size = NULL, $param_file_lim = NULL, $table = 'users')
    {
        if ($param_user_id != null && $param_password != NULL) {
            $password = password_hash($param_password, PASSWORD_DEFAULT);
            $result = $this->db->con->query("UPDATE Users SET username = '{$paramusername}' ,email = '{$param_email}' , password = '{$password}', file_size = {$param_file_size}, file_lim = {$param_file_lim} WHERE id = {$param_user_id} ");
        } else {
            $result = $this->db->con->query("UPDATE Users SET username = '{$paramusername}' ,email = '{$param_email}', file_size = {$param_file_size}, file_lim = {$param_file_lim} WHERE id = {$param_user_id} ");
        }

        return $result;
    }

    //edit user profile 
    public function updateUserPassword($param_user_id = NULL, $param_password = NULL, $table = 'users')
    {
        if ($param_user_id != null && $param_password != NULL) {

            $result = $this->db->con->query("UPDATE {$table} SET password='{$param_password}' WHERE id='{$param_user_id}'");

            return $result;
        }
    }

    //count users 
    public function usersCount($table = 'users')
    {
        if ($this->db->con != null) {
            $result = $this->db->con->query("SELECT id as file_num FROM {$table}");
            return mysqli_num_rows($result);
        }
    }

    //Search for a user
    public function getUsersDataWithSearch($searchTerm, $start_from, $num_per_page)
    {
        $searchTerm = "%{$searchTerm}%";
        $query = "SELECT * FROM users WHERE username LIKE ? LIMIT ?, ?";
        $stmt = $this->db->con->prepare($query);
        $stmt->bind_param("sii", $searchTerm, $start_from, $num_per_page);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
        return $users;
    }
// count users that you searched for 
    public function usersCountWithSearch($searchTerm)
    {
        $searchTerm = "%{$searchTerm}%";
        $query = "SELECT COUNT(*) as count FROM users WHERE username LIKE ?";
        $stmt = $this->db->con->prepare($query);
        $stmt->bind_param("s", $searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();
        $count = $result->fetch_assoc()['count'];
        $stmt->close();
        return $count;
    }

    function checkUserExist($user_id) {
        // Add your database query to check if the user exists
        // Return true if user exists, false otherwise
        // Example:
        $query = "SELECT COUNT(*) as count FROM users WHERE id = ?";
        $stmt = $this->db->con->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $count = $row['count'];
        return $count > 0;
    }

    //logout
    public function logout()
    {
        $_SESSION = array();
        session_destroy();
        header("Location: " . $_SERVER['DOCUMENT_ROOT'] . "/pages/login.php;'");
        # Unset all session variables

    }
    
}
