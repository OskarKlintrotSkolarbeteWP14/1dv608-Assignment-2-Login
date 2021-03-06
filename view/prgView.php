<?php
/**
 * Created by PhpStorm.
 * User: Oskar Klintrot
 * Date: 2015-09-15
 * Time: 14:57
 */

namespace view;

/**
 * Class PrgView
 * @package view
 */
class PrgView
{
    /**
     * Check if the server request is a POST
     *
     * @return bool True if server request is a POST
     */
    public function isPost() {
        if($_POST)
            return true;
        return false;
    }

    /**
     * Reloads the page with GET
     */
    public function reloadPage() {
        header("Location: " . $_SERVER['REQUEST_URI']);
    }
}