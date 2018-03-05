<?php
/**
 * Created by PhpStorm.
 * User: fangle
 * Date: 2018/2/7
 * Time: 16:28
 */

class IpQuery
{
    protected static $bak = 'ip';
    protected static $currentIp;
    protected static $lastIp;

    public static function queryIp()
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET',Config::get('IpQueryGateway'));
        return (string)$response->getBody();
    }

    /**
     * 获取当前ip 如果没有就查询并写入记录
     * @return string
     */
    public static function getCurrentIp()
    {
        if(!self::$currentIp){
            self::$currentIp = self::queryIp();
            file_put_contents(self::$bak,self::$currentIp);
        }

        return self::$currentIp;
    }

    public static function getLastIp()
    {
        if(!self::$lastIp){
            self::$lastIp = file_get_contents(self::$bak);
        }
        return self::$lastIp;
    }

    public static function isChange()
    {
        return self::getLastIp() != self::getCurrentIp();
    }
}