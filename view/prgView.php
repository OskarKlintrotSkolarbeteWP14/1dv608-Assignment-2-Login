<?php
/**
 * Created by PhpStorm.
 * User: Oskar Klintrot
 * Date: 2015-09-15
 * Time: 14:57
 */

namespace view;

class PrgView
{
    public function isPost() {
        if($_POST)
            return true;
        return false;
    }

    public function reloadPage() {
        header("Location: " . $_SERVER['REQUEST_URI']);
    }
}