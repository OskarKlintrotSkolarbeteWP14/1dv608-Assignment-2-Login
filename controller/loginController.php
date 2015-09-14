<?php
/**
 * Created by PhpStorm.
 * User: Oskar Klintrot
 * Date: 2015-09-12
 * Time: 12:48
 */

namespace controller;

require_once("./model/LoginModel.php");
require_once("./model/User.php");
require_once("./view/LoginView.php");

use view;
use model;

class LoginController
{
    private static $LoginView;
    private static $LoginModel;

    public function __construct($model){
        self::$LoginModel = $model;
        self::$LoginView = new view\LoginView(self::$LoginModel);
    }

    public function doLogin() {
        if (self::$LoginView->doTheUserWantToLogout()) {
            self::$LoginModel->logout();
        }
        else if(self::$LoginView->doTheUserWantToLogin() && !self::$LoginModel->isUserLoggedIn()) {
            self::$LoginModel->login(self::$LoginView->getUser());
        }
        if ($_POST) {
            header("Location: " . $_SERVER['REQUEST_URI']);
        }
    }

    public function getLoginView() {
        return self::$LoginView;
    }
}