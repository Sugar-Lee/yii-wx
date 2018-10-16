<?php
/**
 * Created by PhpStorm.
 * User: LaoJiu
 * Date: 2018/9/16
 * Time: 下午3:51
 */

namespace common\components\wechat\src\mini;
use common\components\wechat\src\config\API;
trait MiniProgramTrait
{
    public $appId;
    public $appSecret;
    //  存放access_token的缓存
    protected $cacheKey = 'wx-access-token';

    /**
     * 根据code 换取 session_key:
     * 此返回值内容应该存储，当客户端检测已经过期才需要重新获取
     * @param $code
     * @author sugar
     * @date 2018/10/11 17:02
     * @return mixed
     */
    public function code2Session($code)
    {
        $params = [
            'query'=>[
                'appid'=>$this->appId,
                'secret'=>$this->appSecret,
                'js_code'=>$code,
                'grant_type'=>'authorization_code',
            ]
        ];
        $response = $this->get(API::URL_TO_SESSION,$params);
        if($response->getStatusCode != 200){
            \Yii::error('miniProgram:code2Session--'.self::ERROR_NO_RESPONSE);
        }
        $data = json_decode($response->getBody(),true);
        return $data;
    }

}