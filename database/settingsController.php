<?php

//php UsersServ calss
class settingsController
{
    public $db = null;

    public function __construct(DBController $db)
    {
        if (!isset($db->con))
            return null;
        $this->db = $db;
    }


    //insert into settings table ( insert )
    public function updateDomainName($param_domain = NULL, $table = 'settings')
    {
        if ($param_domain != null) {
            $result = $this->db->con->query("UPDATE `{$table}` SET `domain_name`='{$param_domain}' WHERE id = 1");
            echo "<script>" . "window.location.href='./serverSettings.php';" . "</script>";
            return $result;
        }
    } 

    public function updateFilesType($param_files_type = NULL, $table = 'settings')
    {
        if ($param_files_type != null) {

            $result = $this->db->con->query("UPDATE `{$table}` SET `files_type`='{$param_files_type}' WHERE id = 1");
            echo "<script>" . "window.location.href='./serverSettings.php';" . "</script>";
            return $result;
            
        }
    } 


    //get all user data

    public function getFilesType($table = 'settings')
    {
        $result = $this->db->con->query("SELECT files_type FROM {$table} WHERE id = 1");

            if ($result != false) {
                $resultArray = mysqli_fetch_array($result, MYSQLI_ASSOC);
            }

        return $resultArray;
    }

    public function getServerData($table = 'settings')
    {
        $result = $this->db->con->query("SELECT * FROM {$table} WHERE id = 1");

            if ($result != false) {
                $resultArray = mysqli_fetch_array($result, MYSQLI_ASSOC);
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



    //edit user profile 
    public function updateUserPassword($param_user_id = NULL, $param_password = NULL, $table = 'users')
    {
        if ($param_user_id != null && $param_password != NULL) {

            $result = $this->db->con->query("UPDATE {$table} SET password='{$param_password}' WHERE id='{$param_user_id}'");
           
            return $result;
        }
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
