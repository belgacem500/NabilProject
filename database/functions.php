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