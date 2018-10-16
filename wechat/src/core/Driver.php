<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/10/11
 * Time: 11:04
 */
namespace common\components\wechat\src\core;
use GuzzleHttp\Client;
use yii\base\Component;
class Driver extends Component
{
    public $client;
    /**
     * ERRORS
     */
    const ERROR_NO_RESPONSE = '本次请求并没有得到响应，请检查通讯是否畅通。';

    public function __construct(array $config = [])
    {
        parent::__construct($config);
        $this->client = new Client();
    }

    /**
     * @param $url //请求地址
     * @param array $params //请求参数配置
     * @author sugar
     * @date 2018/10/11 11:07
     * @return mixed
     */
    protected function get($url,$params = []){
        return $this->client->request('GET',$url,$params);
    }

    /**
     * @param $url //请求地址
     * @param array $params //请求参数配置
     * @author sugar
     * @date 2018/10/11 16:35
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    protected function post($url,$params = []){
        return $this->client->request('POST',$url,$params);
    }
}