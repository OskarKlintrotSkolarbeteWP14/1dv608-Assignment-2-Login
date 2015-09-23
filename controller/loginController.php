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
    /**
     * @var view\LoginView
     */
    private static $LoginView;
    /**
     * @var model\LoginModel
     */
    private static $LoginModel;
    /**
     * @var view\PrgView
     */
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
        //var_dump(self::$LoginView->isCorrectSession());

        if (self::$LoginView->theUserWantToLogout()){
            self::$LoginView->setLogoutView();
            self::$LoginModel->logout();
            $tempUsername = self::$LoginView->removeKeepLogin();
            self::$LoginModel->removeUser($tempUsername);
        }
        else if(self::$LoginView->theUserWantToLogin()) {
            self::$LoginView->setLoginView();
            $successfulLogin = self::$LoginModel->login(self::$LoginView->getUser());
            if($successfulLogin && self::$LoginView->isKeepLoggedInChecked()) {
                $randomizedPassword = self::$LoginModel->saveUser(self::$LoginView->getUser());
                self::$LoginView->setKeepLogin($randomizedPassword);
            }
        }
        else if(self::$LoginView->checkIfPersistentLoggedIn()) {
            self::$LoginModel->loginSavedUser(self::$LoginView->getPersistentLoggedInUser());
            self::$LoginView->setLoginWithCookiesView();
        }
        else if (!self::$LoginView->validCookies()) {
//            self::$LoginModel->logout();
            self::$LoginView->removeKeepLogin();
            self::$LoginView->setFailedLoginWithCookiesView();
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