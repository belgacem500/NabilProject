<?php



//require my SQL Connection

require('DBController.php');

//require my SQL Connection

require('userController.php');
require('filesController.php');
require('settingsController.php');


//DBController object
$db = new DBController();
$reg = new userController($db);
$fileCont = new fileController($db);
$settingsCont = new settingsController($db);

$row = $settingsCont->getServerData();

define("UPLOAD_SERVER", 'http://'.$row['domain_name']);

if($row['upload_folder']!="") {
    define("UPLOAD_FOLDER", $row['upload_folder']);
}else{
    define("UPLOAD_FOLDER", "uploads");
}

