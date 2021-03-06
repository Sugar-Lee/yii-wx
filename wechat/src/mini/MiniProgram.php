<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/10/10
 * Time: 15:51
 */
namespace common\components\wechat\src\mini;
use yii\base\Component;
use common\components\wechat\src\core\AccessToken;
use common\utils\ClientUtils;
class MiniProgram extends Component
{
    use MiniProgramTrait;
    public $appId;
    public $appSecret;
    public $code;
    public $mchId;
    public $apiKey;
    public $certPem;
    public $keyPem;
    public $sessionKey;
    public $cache;

    public $client;
    private $accessToken;

    public function init()
    {
        parent::init();
        $this->accessToken = (new AccessToken(['appId'=>$this->appId,'appSecret'=>$this->appSecret]))->getToken();
        $this->client = new ClientUtils([]);

    }

    /**
     * 获取session
     * @author sugar
     * @date 2018/10/11 14:51
     * @return mixed
     * @throws \ErrorException
     */
    public function getSession()
    {
        $data = $this->code2Session($this->code);
        return $data;
    }

    /**
     * 解密userInfo
     * @param $encryptedData
     * @param $iv
     * @author sugar
     * @date 2018/10/15 19:18
     * @return array
     */
    public function decryptData($encryptedData, $iv)
    {
        if (!$this->sessionKey) {
            throw new \InvalidArgumentException('sessionKey cannot be blank!');
        }
        $aes = new MiniAES($this->appId, $this->sessionKey);
        $data = $aes->decrypt(base64_decode($encryptedData), base64_decode($iv));
        return $data?json_decode($data,true):[];
    }

    /**
     * 生成一个不限制的小程序码
     * @param $scene
     * @param $page
     * @param array $extra
     * @author sugar
     * @date 2018/10/12 17:37
     * @return \Psr\Http\Message\StreamInterface
     */
    public function getUnLimit($scene,$page,$extra = []){
//        $qr = new MiniQrcode(['appId'=>$this->appId,'appSecret'=>$this->appSecret]);
        return $this->unLimit($scene,$page,$extra);
    }
}