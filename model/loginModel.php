<?php
/**
 * Created by PhpStorm.
 * User: Oskar Klintrot
 * Date: 2015-09-12
 * Time: 13:03
 */

namespace model;

class LoginModel
{
    private static $username = "Admin";
    private static $password = "Password";
    private static $loggedIn = "LoggedInSessionVariable";

    public function checkCredential(User $User) {
        return $User->getUsername() == self::$username && $User->getPassword() == self::$password;
    }

    public function login(User $User)
    {
        $_SESSION[self::$loggedIn] = $User->getUsername() == self::$username && $User->getPassword() == self::$password;
    }

    public function logout()
    {
        $_SESSION[self::$loggedIn] = false;
    }

    public function isUserLoggedIn(){
        if (empty($_SESSION[self::$loggedIn]))
            return false;
        return $_SESSION[self::$loggedIn];
    }
}