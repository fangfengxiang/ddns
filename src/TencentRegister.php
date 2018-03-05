<?php
/**
 * Created by PhpStorm.
 * User: fangle
 * Date: 2018/2/7
 * Time: 18:16
 */

class TencentRegister implements RegisterInterface
{
    protected $api;
    protected $secretId;
    protected $secretKey;
    protected $method;
    protected $domain;
    protected $subDomain;
    protected $action;

    public function __construct($config)
    {
        $this->api = $config['api'];
        $this->secretId = $config['secretId'];
        $this->secretKey = $config['secretKey'];
        $this->method = $config['method'];
        $this->domain = $config['domain'];
        $this->subDomain = $config['subDomain'];
    }

    /**
     * 生成公共参数
     * @return array
     */
    protected function createCommonParams()
    {
        return [
            'Action'=>$this->action,
            'Timestamp'=>time(),//当前时间戳
            //'Region'=>'ap-guangzhou',
            'Nonce'=>mt_rand(10000,9999999),//随机正整数5位到7位
            'SignatureMethod'=>'HmacSHA256',
            'SecretId'=>$this->secretId,
        ];
    }

    /**
     * 生成签名
     * @param $params
     * @return string
     */
    protected function createSignature($params)
    {
        //1 参数排序
        ksort($params);
        //2 拼接字符串
        $parStr = '';
        foreach($params as $key=>$val){
            $parStr = $parStr.$key.'='.$val.'&';
        }
        $parStr = rtrim($parStr,'&');
        // 3 拼接原文字符串
        $srcStr = $this->method
            .str_replace('https://','',$this->api)
            .'?'.$parStr;
        // 4 生成签名串
        $secretKey = $this->secretKey;
        $signStr = base64_encode(hash_hmac('sha256', $srcStr, $secretKey, true));
        return $signStr;
    }

    /**
     * 生成参数数组
     * @return array
     */
    protected function createParams($apiParams)
    {
        //1 获取全部参数数组
        $params = array_merge(
            $this->createCommonParams(),
            $apiParams
        );
        // 2 生成签名字符串
        $params['Signature'] = $this->createSignature($params);
        // 3 如果get请求所有参数都要urlencode post只需encode签名字符串
        // 因为使用了guzzle发起请求，数组传给guzzle时,guzzle会帮你urlencode
        // 所以移走了这里的urlencode 代码。如果你urlencode了反而会出错
        return $params;
    }

    /**
     * 发送接口请求
     * @param $params
     * @return string
     */
    public function send($params)
    {
        $client = new \GuzzleHttp\Client();

        $options = $this->method == 'GET'
            ? ['query'=>$params]
            : ['form_params'=>$params];

        $response = $client->request($this->method,$this->api,$options);

        return (string)$response->getBody();
    }

    public function create($ip)
    {
        //请求api对应的action
        $this->action = 'RecordCreate';
        //该接口特有的参数
        $apiParams = [
            'domain'=>$this->domain,
            'subDomain'=>$this->subDomain,
            'recordType'=>'A',
            'recordLine'=>'默认',
            'value'=>$ip,
        ];
        // 生成参数数组
        $params = $this->createParams($apiParams);
        // 发送请求
        return $this->send($params);
    }

    public function delete()
    {

    }

    public function modify($ip)
    {
        // TODO: Implement modify() method.
        //请求api对应的action
        $this->action = 'RecordModify';
        //该接口特有的参数
        $apiParams = [
            'domain'=>$this->domain,
            'subDomain'=>$this->subDomain,
            'recordType'=>'A',
            'recordLine'=>'默认',
            'recordId'=>file_get_contents('recordId'),
            'value'=>$ip,
        ];
        // 生成参数数组
        $params = $this->createParams($apiParams);
        // 发送请求
        return $this->send($params);
    }

    public function lists()
    {
        // TODO: Implement lists() method.
    }

    public function status()
    {
        // TODO: Implement status() method.
    }
}