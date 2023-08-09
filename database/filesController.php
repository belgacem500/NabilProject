<?php

//php UsersServ calss
class fileController
{
    public $db = null;

    public function __construct(DBController $db)
    {
        if (!isset($db->con))
            return null;
        $this->db = $db;
    }

    //insert into user table ( insert )



    public function addFile($uploader_id=null, $file_name=null, $target_file=null, $file_type=null, $file = null, $table = "files")
    {

        if ($this->db->con != null) {
            if ($uploader_id != null && $file_name != null && $target_file != null && $file_type != null) {
                //create sql query
                $query_string = sprintf("INSERT INTO {$table} (`uploader_id`, `file_name`, `file_loc`, `file_type`) VALUES ('{$uploader_id}', '{$file_name}', '{$target_file}' , '{$file_type}')");
                move_uploaded_file($file,'../'. UPLOAD_FOLDER . '/' . $target_file);
                //execute query
                $result = $this->db->con->query($query_string);
                echo "<script>" . "window.location.href='./files.php';" . "</script>";
                return $result;
            }
        }
    }


    public function filesCountById($user_id = null, $table = "files")
    {
        if ($this->db->con != null) {
            $result = $this->db->con->query("SELECT COUNT(id) as file_num FROM {$table} WHERE uploader_id = {$user_id}");
            return mysqli_fetch_assoc($result);
        }
    }

    public function getFilesData($start_from = 0, $num_per_page = 0)
    {
        if ($this->db->con != null) {
            $result = $this->db->con->query("SELECT files.* , users.username , users.folder_name FROM `users` , `files` WHERE users.id = files.uploader_id limit {$start_from},{$num_per_page} ");

            return $result;
        }
    }

    public function filesCount($table = "files")
    {
        if ($this->db->con != null) {
            $result = $this->db->con->query("SELECT id as file_num FROM {$table}");
            return mysqli_num_rows($result);
        }
    }

    public function getFileCountById($uploader_id = null, $table = 'files')
    {
        if ($this->db->con != null) {
            if (isset($uploader_id)) {

                $result = $this->db->con->query("SELECT COUNT(id) as file_num FROM files WHERE uploader_id = '{$uploader_id}'");

                if ($result != false) {
                    return mysqli_fetch_assoc($result);
                }
            }

            return mysqli_fetch_assoc($result);
        }
    }

    public function filesDataById($user_id = null, $start_from = 0, $num_per_page = 0, $table = 'files')
    {
        if ($this->db->con != null) {
            if (isset($user_id)) {
                $result = $this->db->con->query("SELECT * FROM {$table} WHERE uploader_id = {$user_id}  limit {$start_from},{$num_per_page}");
            }

            return $result;
        }
    }


    # function to delete the file
    public function deleteFile($file_id = null, $file_name = Null,  $folder_name = Null, $table = 'files')
    {
        if ($this->db->con != null) {
            if ($file_id != null) {

                unlink($_SERVER['DOCUMENT_ROOT'] . '/' . UPLOAD_FOLDER . '/' . $folder_name . '/' . $file_name);
                $result = $this->db->con->query("DELETE FROM {$table} WHERE id={$file_id}");

                if ($result) {
                    header("Location:" . $_SERVER['PHP_SELF']);
                }
                return $result;
            }
        }
    }
}
