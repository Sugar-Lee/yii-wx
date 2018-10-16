<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/10/11
 * Time: 15:37
 */
namespace common\components\wechat\src\mini;
use common\components\wechat\src\config\API;
use common\components\wechat\src\core\AccessToken;
use common\components\wechat\src\core\Driver;

class MiniQrcode extends Driver
{
    public $appId;
    public $appSecret;
    private $accessToken = null;

    public function __construct(array $config = [])
    {
        parent::__construct($config);
        $this->accessToken = (new AccessToken(['appId'=>$this->appId,'appSecret'=>$this->appSecret]))->getToken();
    }

    /**
     * 生成一个不限制的小程序码
     * @param $scene
     * @param $page //路径，不能带阐述
     * @param array $extra
     * @author sugar
     * @date 2018/10/11 17:53
     * @return \Psr\Http\Message\StreamInterface
     */
    public function unLimit($scene,$page,$extra = []){
        $params = ['json' =>array_merge(['scene'=>$scene,'page'=>$page],$extra)];
        $response = $this->post(API::URL_UN_LIMIT_CREATE."?access_token=".$this->accessToken,$params);

        if($response->getStatusCode != 200){
            \Yii::error('miniProgram:code2Session--'.self::ERROR_NO_RESPONSE);
        }

        $contentType = $response->getHeaders()['content-type'];
        if(strpos($contentType,'json') != false){
            $data = $response->getBody();
            if(isset($data['errcode'])){
                \Yii::error('miniProgram:unLimit--'.$data['errcode'].$data['errmsg']);
            }
        }
        return $response->getBody();
    }

    /**
     * 生成永久小程序码
     * 数量有限
     * @param $path
     * @param array $extra
     * @author sugar
     * @date 2018/10/11 17:53
     * @return \Psr\Http\Message\StreamInterface
     */
    public function forever($path,$extra = []){
        $params = array_merge(['path'=>$path],$extra);
        $response = $this->post(API::URL_CREATE."?access_token=".$this->accessToken,$params);

        if($response->getStatusCode != 200){
            \Yii::error('miniProgram:code2Session--'.self::ERROR_NO_RESPONSE);
        }
        $contentType = $response->getHeaders()['content-type'];
        if(strpos($contentType,'json') != false){
            $data = $response->getBody();
            if(isset($data['errcode'])){
                \Yii::error('miniProgram:forever--'.$data['errcode'].$data['errmsg']);
            }
        }
        return $response->getBody();
    }
}