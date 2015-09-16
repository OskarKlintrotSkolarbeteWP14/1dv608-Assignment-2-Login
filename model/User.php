<?php
/**
 * Created by PhpStorm.
 * User: Oskar Klintrot
 * Date: 2015-09-13
 * Time: 14:01
 */

namespace model;

/**
 * Class User
 * @package model
 */
class User
{
    private $username;
    private $password;

    /**
     * Creates the user, both username and password can be null
     * @param string $username
     * @param string $password
     */
    public function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * @return string The users username
     */
    public function getUsername(){
        return $this->username;
    }

    /**
     * @return string The users password
     */
    public function getPassword(){
        return $this->password;
    }
}