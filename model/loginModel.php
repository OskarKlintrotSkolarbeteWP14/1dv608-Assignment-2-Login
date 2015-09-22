<?php
/**
 * Created by PhpStorm.
 * User: Oskar Klintrot
 * Date: 2015-09-12
 * Time: 13:03
 */

namespace model;

/**
 * Class LoginModel
 * @package model
 */
class LoginModel
{
    private static $username = "Admin";
    private static $password = "Password";
    private static $loggedIn = "LoggedInSession";
    private static $folder = "./model/persistentLogin/";

    /** Returns a randomized string
     *
     * From http://stackoverflow.com/questions/19017694/one-line-php-random-string-generator
     *
     * @return string
     */
    private function createRandomString() {
        return rtrim(base64_encode(md5(microtime())),"=");
    }

    /**
     * @param User $user User to be authenticated
     * @return bool True if the login succeed, otherwise returns false
     */
    public function checkCredential(User $user) {
        return $user->getUsername() == self::$username && $user->getPassword() == self::$password;
    }

    /**
     * Logs in the user
     * @param User $user User to login
     */
    public function login(User $user)
    {
        $_SESSION[self::$loggedIn] = $this->checkCredential($user);

        return $_SESSION[self::$loggedIn];
    }

    /**
     * Logout the user
     */
    public function logout()
    {
        $_SESSION[self::$loggedIn] = false;
    }

    /**
     * @return bool True if the user is logged in, otherwise false
     */
    public function isUserLoggedIn(){
        if (empty($_SESSION[self::$loggedIn]))
            return false;
        return $_SESSION[self::$loggedIn];
    }

    public function saveUser(User $user) {
        $randomizedPassword = $this->createRandomString();
        file_put_contents($this->getFileName($user->getUsername()), $randomizedPassword);
        return $randomizedPassword;
    }

    public function removeUser($username) {
        unlink($this->getFileName($username));
    }

    public function checkCredentialForSavedUser(User $user) {
        try { // TODO: Can't catch an error, flippin PHP...
            return $user->getPassword() == file_get_contents($this->getFileName($user->getUsername()));
        }
        catch (\Exception $e) {
            return false;
        }
    }

    public function loginSavedUser(User $user) {
        $_SESSION[self::$loggedIn] = $this->checkCredentialForSavedUser($user);
    }

    public function getFileName($username) {
        return self::$folder . $username;
    }
}