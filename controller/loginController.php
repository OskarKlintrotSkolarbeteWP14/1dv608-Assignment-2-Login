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
require_once("./view/PrgView.php");

use view;
use model;

class LoginController
{
    private static $LoginView;
    private static $LoginModel;
    private static $PrgView;

    public function __construct($model){
        self::$LoginModel = $model;
        self::$LoginView = new view\LoginView(self::$LoginModel);
        self::$PrgView = new view\PrgView();
    }

    public function doLogin() {
        if (self::$LoginView->doTheUserWantToLogout() && self::$LoginModel->isUserLoggedIn()) {
            self::$LoginView->setLogoutView();
            self::$LoginModel->logout();
        }
        else if(self::$LoginView->doTheUserWantToLogin() && !self::$LoginModel->isUserLoggedIn()) {
            self::$LoginView->setLoginView();
            self::$LoginModel->login(self::$LoginView->getUser());
        }
        if (self::$PrgView->isPost()) {
            self::$PrgView->reloadPage();
        }
    }

    public function getLoginView() {
        return self::$LoginView;
    }
}