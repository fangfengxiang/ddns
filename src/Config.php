<?php
/**
 * Created by PhpStorm.
 * User: fangle
 * Date: 2018/2/8
 * Time: 21:53
 */

class Config
{
    protected static $config;

    public static function get($key=null)
    {
        if(is_null(self::$config)){
            self::$config = include 'config.php';
        }

        if(is_null($key)){
            $config = self::$config;
        }else{
            $config = self::$config[$key];
        }

        return $config;
    }
}