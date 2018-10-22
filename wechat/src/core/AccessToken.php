<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/10/11
 * Time: 17:56
 */
namespace common\components\wechat\src\core;
use common\components\wechat\src\config\Config;
use common\utils\ClientUtils;
class AccessToken
{
    public $appId;
    public $appSecret;
    public $cacheKey = 'wx-access-token';

    public function __construct(array $config = [])
    {
        $this->appSecret = $config['appSecret'];
        $this->appId = $config['appId'];
    }

    /**
     * 获得access_token
     *
     * @param $cacheRefresh boolean 是否刷新缓存
     * @author abei<abei@nai8.me>
     * @return string
     */
    public function getToken($cacheRefresh = true){
        $cacheKey = "{$this->cacheKey}-{$this->appId}";
        if($cacheRefresh == true){
            \Yii::$app->dbCache->delete($cacheKey);
        }
        $data = \Yii::$app->dbCache->get($cacheKey);
        if($data == false){
            $token = $this->getTokenFromServer();
            if(isset($token['access_token'])){
                $data = $token['access_token'];
                \Yii::$app->dbCache->set($cacheKey,$data,$token['expires_in']-1200);
            }
        }
        return $data;
    }

    /**
     * 从服务器上获得accessToken。
     * @author sugar
     * @date 2018/10/11 15:56
     * @return mixed
     */
    public function getTokenFromServer(){
        $params = [
            'query' => [
                'appid' => $this->appId,
                'secret' => $this->appSecret,
                'grant_type' => 'client_credential',
            ]
        ];
        $response = (new ClientUtils([]))->get(Config::URL_TOKEN_GET,$params);
        // 状态码
        // response 具体内容
        if($response->getStatusCode() != 200){
            \Yii::error('miniProgram:getTokenFromServer--'.$response->getStatusCode());
        }
        $data = json_decode($response->getBody(),true);
        if(!isset($data['access_token'])){
            \Yii::error('miniProgram:getTokenFromServer--'.$data['errcode'].$data['errmsg']);
        }
        return $data;
    }

}