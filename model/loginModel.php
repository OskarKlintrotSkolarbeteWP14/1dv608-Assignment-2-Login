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
    /**
     * Username
     *
     * @var string
     */
    private static $username = "Admin";
    /**
     * Password
     *
     * @var string
     */
    private static $password = "Password";
    /**
     * Name of session variable
     *
     * @var string
     */
    private static $loggedIn = "LoggedInSession";
    /**
     * Path to where the users are stored
     *
     * @var string
     */
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
     * Validates the credentials for the user
     *
     * @param User $user User to be authenticated
     * @return bool True if the login succeed, otherwise returns false
     */
    public function checkCredential(User $user) {
        return $user->getUsername() == self::$username && $user->getPassword() == self::$password;
    }

    /**
     * Login the user
     *
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
     * Check if the user is currently logged in
     *
     * @return bool True if the user is logged in, otherwise false
     */
    public function userLoggedIn(){
        if (empty($_SESSION[self::$loggedIn]))
            return false;
        return $_SESSION[self::$loggedIn];
    }

    /**
     * Saves the user and a randomized password
     *
     * @param User $user User to be saved
     * @return string The randomized password
     */
    public function saveUser(User $user) {
        $randomizedPassword = $this->createRandomString();
        file_put_contents($this->getFileName($user->getUsername()), $randomizedPassword);
        return $randomizedPassword;
    }

    /**
     * Removes a user
     *
     * @param $username Username of the user to be removed
     */
    public function removeUser($username) {
        unlink($this->getFileName($username));
    }

    /**
     * Validates a user that is supposed to be saved
     *
     * @param User $user User to be validated
     * @return bool
     */
    public function checkCredentialForSavedUser(User $user) {
        try { // TODO: Can't catch an error, flippin PHP...
            return $user->getPassword() == file_get_contents($this->getFileName($user->getUsername()));
        }
        catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Login a saved user again
     *
     * @param User $user User to be logged in
     */
    public function loginSavedUser(User $user) {
        $_SESSION[self::$loggedIn] = $this->checkCredentialForSavedUser($user);
    }

    /**
     * Returns the path and filename to file that
     * stores credentials for a user
     *
     * @param $username Username of user to be retrieved
     * @return string Path to file
     */
    public function getFileName($username) {
        return self::$folder . $username;
    }
}