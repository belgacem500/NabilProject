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

    // add a file to the list of files allowed to upload
    public function updateFilesType($param_files_type = NULL, $table = 'settings')
    {
        if ($param_files_type != null) {

            $result = $this->db->con->query("UPDATE `{$table}` SET `files_type`='{$param_files_type}' WHERE id = 1");
            echo "<script>" . "window.location.href='./serverSettings.php';" . "</script>";
            return $result;
        }
    }

    // to edit the folder that contains the users folders name , if empty it will be the default value 'uploads'
    public function updateUploadFolder($param_folder_name = NULL, $table = 'settings')
    {

        if ($param_folder_name !== "") {

                $oldFolderName = '../'.UPLOAD_FOLDER ; // Replace with the current folder name
                $newFolderName = '../'.$param_folder_name; // Replace with the new folder name
                // Rename the folder
                if (rename($oldFolderName, $newFolderName)) {
                    $result = $this->db->con->query("UPDATE `{$table}` SET `upload_folder`='{$param_folder_name}' WHERE id = 1");
                }

        } else {
            $oldFolderName = '../'.UPLOAD_FOLDER ; // Replace with the current folder name
            $newFolderName = '../'."uploads"; // Replace with the new folder name
            // Rename the folder
            if (rename($oldFolderName, $newFolderName)) {
                $result = $this->db->con->query("UPDATE `{$table}` SET `upload_folder`='{$param_folder_name}' WHERE id = 1");
            }
        }
        echo "<script>" . "window.location.href='./serverSettings.php';" . "</script>";
        return $result;

    }


    //get all user data

    public function getServerData($table = 'settings')
    {
        $result = $this->db->con->query("SELECT * FROM {$table} WHERE id = 1");

        if ($result != false) {
            $resultArray = mysqli_fetch_array($result, MYSQLI_ASSOC);
        }

        return $resultArray;
    }

    //get upload folder name
    public function getUploadFolder($table = 'settings')
    {
        $result = $this->db->con->query("SELECT upload_folder FROM {$table} WHERE id = 1");

        if ($result != false) {
            $resultArray = mysqli_fetch_array($result, MYSQLI_ASSOC);
        }

        return $resultArray;
    }

    //get files type allowed to be uploaded
    public function getFilesType($table = 'settings')
    {
        $result = $this->db->con->query("SELECT files_type FROM {$table} WHERE id = 1");

        if ($result != false) {
            $resultArray = mysqli_fetch_array($result, MYSQLI_ASSOC);
        }

        return $resultArray;
    }
}
