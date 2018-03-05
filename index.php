<?php
require 'vendor/autoload.php';
require 'src/IpQuery.php';
require 'src/DnsFactory.php';
require 'src/RegisterInterface.php';
require 'src/TencentRegister.php';
require 'src/Config.php';


IpQuery::isChange() or die('该域名正常解析中');

// 获取一个DNS注册器
$register = DnsFactory::getRegister('tencent');

//修改为域名解析 为当前的公网ip
$response = $register->modify(IpQuery::getCurrentIp());
$res = json_decode($response,true);

// 如果是InvalidParameter 说明没创建过
if($res['code']==4000 && $res['codeDesc']=='InvalidParameter'){
    $response = $register->create(IpQuery::getCurrentIp());
    $res = json_decode($response,true);
    file_put_contents('recordId',$res['data']['record']['id']);
}

if($res['code']==0){
    echo '指定域名解析成功';
}else{
    echo '解析失败';
    file_put_contents('register.log',print_r($res,true),FILE_APPEND);
    file_put_contents('ip','');//置空文件缓存中的公网ip
}



