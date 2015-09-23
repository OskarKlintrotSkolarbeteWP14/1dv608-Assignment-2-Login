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
        //var_dump(self::$LoginView->isCorrectSession());

        if ((self::$LoginView->theUserWantToLogout() && self::$LoginModel->isUserLoggedIn())
                || !self::$LoginView->isCorrectSession()){
            if (self::$LoginView->isCorrectSession())
                self::$LoginView->setLogoutView();
            self::$LoginModel->logout();
            $tempUser = self::$LoginView->removeKeepLogin();
            self::$LoginModel->removeUser($tempUser);
        }
        else if(self::$LoginView->theUserWantToLogin() && !self::$LoginModel->isUserLoggedIn()) {
            self::$LoginView->setLoginView();
            $successfulLogin = self::$LoginModel->login(self::$LoginView->getUser());
            if($successfulLogin && self::$LoginView->isKeepLoggedInChecked()) {
                $randomizedPassword = self::$LoginModel->saveUser(self::$LoginView->getUser());
                self::$LoginView->setKeepLogin($randomizedPassword);
            }
        }
        else if(self::$LoginView->checkIfPersistentLoggedIn() && !self::$LoginView->isPhpSession()) {
            self::$LoginModel->loginSavedUser(self::$LoginView->getLoggedInUser());
            self::$LoginView->setLoginWithCookiesView();
        }
        else if (!self::$LoginView->checkIfPersistentLoggedIn()) {
            self::$LoginView->removeKeepLogin();
            if (self::$LoginView->isAnyCookiesSet()) {
                self::$LoginView->setFailedLoginWithCookiesView();
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