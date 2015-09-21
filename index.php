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

session_start();

//CREATE OBJECTS OF THE VIEWS
$LoginModel = new \model\LoginModel();
$LoginController = new \controller\LoginController($LoginModel);
$LoginView = $LoginController->getLoginView();
$DateTimeView = new \view\DateTimeView();
$LayoutView = new \view\LayoutView();

$LoginController->doLogin();
$LayoutView->render($LoginModel->isUserLoggedIn(), $LoginView, $DateTimeView);

// echo "Server request method";
// echo var_dump($_SERVER['REQUEST_METHOD']);

//var_dump($_SESSION["LoggedInSession"]);
//if(isset($_COOKIE["LoginView::CookieName"]) || isset($_COOKIE["LoginView::CookiePassword"])) {
//    if (isset($_COOKIE["LoginView::CookieName"]))
//        var_dump($_COOKIE["LoginView::CookieName"]);
//    else
//        echo 'No username cookie found';
//    if (isset($_COOKIE["LoginView::CookiePassword"]))
//        var_dump($_COOKIE["LoginView::CookiePassword"]);
//    else
//        echo 'No password cookie found';
//}
//else
//    echo 'No cookies found';