<?php
/**
 * Created by PhpStorm.
 * User: Oskar Klintrot
 * Date: 2015-09-22
 * Time: 14:46
 */

namespace model;


class UserClient
{
    private static $IpAddress;
    private static $Browser;
    private static $SessionName;

    public function __construct($sessionName, $ipAddress, $browser) {
        self::$SessionName = $sessionName;
        self::$IpAddress = $ipAddress;
        self::$Browser = $browser;
    }

    public function getSessionName() {
        return self::$SessionName;
    }

    public function getIpAddress() {
        return self::$IpAddress;
    }

    public function getBrowser() {
        return self::$Browser;
    }
}