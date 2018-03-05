<?php
/**
 * Created by PhpStorm.
 * User: fangle
 * Date: 2018/2/8
 * Time: 18:13
 */
return [
    'IpQueryGateway'=>'http://193.112.4.25/getIp.php',//你必须先写一个脚本返回请求客户端的公网IP

    //以下是你的DNS解析器配置
    // 我只实现了腾讯的云解析，如果你是其他的云服务商 你只需要实现RegisterInterface
    'tencent'=>[
        'api'  =>'https://cns.api.qcloud.com/v2/index.php',
        'secretId'=>'',  //上腾讯云 拿到你的 secretId 和 secretKey
        'secretKey'=>'', //
        'method'=>'POST',
        'domain'=>'fangle-coder.cn',
        'subDomain'=>'raspiberry',
    ],
];