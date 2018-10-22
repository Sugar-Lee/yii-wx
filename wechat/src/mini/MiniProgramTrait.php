<?php
/**
 * Created by PhpStorm.
 * User: LaoJiu
 * Date: 2018/9/16
 * Time: 下午3:51
 */

namespace common\components\wechat\src\mini;
use common\components\wechat\src\config\Config;

trait MiniProgramTrait
{
    protected $cacheKey = 'wx-access-token';//  存放access_token的缓存
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
        $response = $this->client->get(Config::URL_TO_SESSION,$params);

        if($response->getStatusCode != 200){
            \Yii::error('miniProgram:code2Session--'.$response->getStatusCode);
        }
        $data = json_decode($response->getBody(),true);
        return $data;
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
        $response = $this->client->post(Config::URL_UN_LIMIT_CREATE."?access_token=".$this->accessToken,$params);

        if($response->getStatusCode != 200){
            \Yii::error('miniProgram:unLimit--'.$response->getStatusCode);
        }

        $contentType = $response->getHeaders()['Content-Type'];
        if(strpos($contentType[0],'json') != false){
            $data = json_decode($response->getBody(),true);
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
        $response = $this->client->post(Config::URL_CREATE."?access_token=".$this->accessToken,$params);

        if($response->getStatusCode != 200){
            \Yii::error('miniProgram:forever--'.$response->getStatusCode);
        }
        $contentType = $response->getHeaders()['Content-Type'];
        if(strpos($contentType[0],'json') != false){
            $data = json_decode($response->getBody(),true);
            if(isset($data['errcode'])){
                \Yii::error('miniProgram:forever--'.$data['errcode'].$data['errmsg']);
            }
        }
        return $response->getBody();
    }

}