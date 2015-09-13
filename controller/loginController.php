<?php
/**
 * Created by PhpStorm.
 * User: Oskar Klintrot
 * Date: 2015-09-12
 * Time: 12:48
 */

namespace controller;

require_once("./model/LoginModel.php");
require_once("./view/LoginView.php");

class LoginController
{
    private $LoginView;
    private $LoginModel;

    public function __construct($model){
        $this->LoginModel = $model;
        $this->LoginView = \view\LoginView($this->LoginModel);
    }
}