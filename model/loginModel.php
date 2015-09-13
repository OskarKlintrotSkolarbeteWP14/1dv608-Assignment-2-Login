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
    private static $loggedIn;

    public function login($username, $password)
    {
        self::$loggedIn = $username == self::$username && $password == self::$password;
        return self::$loggedIn;
    }

    public function isUserLoggedIn(){
        if (empty(self::$loggedIn))
            return false;
        return self::$loggedIn;
    }
}