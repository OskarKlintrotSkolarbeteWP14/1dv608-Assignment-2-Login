<?php
/**
 * Created by PhpStorm.
 * User: Oskar Klintrot
 * Date: 2015-09-13
 * Time: 14:01
 */

namespace model;

class User
{
    private $username;
    private $password;

    public function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    public function getUsername(){
        return $this->username;
    }

    public function getPassword(){
        return $this->password;
    }
}