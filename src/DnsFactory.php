<?php
/**
 * Created by PhpStorm.
 * User: fangle
 * Date: 2018/2/7
 * Time: 17:26
 */

class DnsFactory
{
    protected static $registers;

    public static function getRegister(string $serverName):RegisterInterface
    {
        $className = ucfirst($serverName).'Register';

        if(!isset(self::$registers[$serverName]))
            self::$registers[$serverName] = new $className(Config::get($serverName));

        return self::$registers[$serverName];
    }
}