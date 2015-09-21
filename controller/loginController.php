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

/**
 * Class LoginController
 * @package controller
 */
class LoginController
{
    private static $LoginView;
    private static $LoginModel;
    private static $PrgView;

    /**
     * @param model\LoginModel $model
     */
    public function __construct(\model\LoginModel $model){
        self::$LoginModel = $model;
        self::$LoginView = new view\LoginView(self::$LoginModel);
        self::$PrgView = new view\PrgView();
    }

    /**
     * Function for handling states associated with the login
     */
    public function doLogin() {
        if (self::$LoginView->doTheUserWantToLogout() && self::$LoginModel->isUserLoggedIn()) {
            self::$LoginView->setLogoutView();
            self::$LoginModel->logout();
            self::$LoginView->removeKeepLogin();
        }
        else if(self::$LoginView->doTheUserWantToLogin() && !self::$LoginModel->isUserLoggedIn()) {
            self::$LoginView->setLoginView();
            $successfulLogin = self::$LoginModel->login(self::$LoginView->getUser());
            if($successfulLogin && self::$LoginView->isKeepLoggedInChecked()) {
                self::$LoginView->setKeepLogin();
            }
        }
        if (self::$PrgView->isPost()) {
            self::$PrgView->reloadPage();
        }
    }

    /**
     * @return view\LoginView
     */
    public function getLoginView() {
        return self::$LoginView;
    }
}