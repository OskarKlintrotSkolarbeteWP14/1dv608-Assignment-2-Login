<?php

//INCLUDE THE FILES NEEDED...
require_once('view/LoginView.php');
require_once('view/DateTimeView.php');
require_once('view/LayoutView.php');
require_once('model/LoginModel.php');
require_once('controller/LoginController.php');

//MAKE SURE ERRORS ARE SHOWN... MIGHT WANT TO TURN THIS OFF ON A PUBLIC SERVER
error_reporting(E_ALL);
ini_set('display_errors', 'On');

//CREATE OBJECTS OF THE VIEWS
$LoginModel = new \model\LoginModel();
$LoginView = new LoginView($LoginModel);
$DateTimeView = new DateTimeView();
$LayoutView = new LayoutView();


$LayoutView->render(false, $LoginView, $DateTimeView);

// echo var_dump($_SERVER['REQUEST_METHOD']);