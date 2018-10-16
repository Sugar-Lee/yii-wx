<?php
/**
 * Created by PhpStorm.
 * User: LaoJiu
 * Date: 2018/9/16
 * Time: 下午8:07
 */

namespace common\components\wechat\src\mini;


class MiniAES
{
    private $appId;
    private $sessionKey;

    /**
     * 构造函数
     * @param $sessionKey string 用户在小程序登录后获取的会话密钥
     * @param $appId string 小程序的appId
     */
    public function __construct($appId, $sessionKey)
    {
        $this->sessionKey = $sessionKey;
        $this->appId = $appId;
    }


    /**
     * @param string $text
     * @param string $key
     * @param string $iv
     * @param int    $option
     *
     * @return string
     */
    public function encrypt($text, $iv, $option = OPENSSL_RAW_DATA)
    {
        self::validateKey(base64_decode($this->sessionKey));
        self::validateIv($iv);

        return openssl_encrypt($text, self::getMode(base64_decode($this->sessionKey)), base64_decode($this->sessionKey), $option, $iv);
    }

    /**
     * @param string      $cipherText
     * @param string      $key
     * @param string      $iv
     * @param int         $option
     * @param string|null $method
     *
     * @return string
     */
    public function decrypt($cipherText, $iv, $option = OPENSSL_RAW_DATA, $method = null)
    {
        self::validateKey(base64_decode($this->sessionKey));
        self::validateIv($iv);

        return openssl_decrypt($cipherText, $method ?: self::getMode(base64_decode($this->sessionKey)), base64_decode($this->sessionKey), $option, $iv);
    }

    /**
     * @param string $key
     *
     * @return string
     */
    public function getMode($key)
    {
        return 'aes-'.(8 * strlen($key)).'-cbc';
    }

    /**
     * @param string $key
     */
    public function validateKey($key)
    {
        if (!in_array(strlen($key), [16, 24, 32], true)) {
            throw new \InvalidArgumentException(sprintf('Key length must be 16, 24, or 32 bytes; got key len (%s).', strlen($key)));
        }
    }

    /**
     * @param string $iv
     *
     * @throws \InvalidArgumentException
     */
    public function validateIv($iv)
    {
        if (!empty($iv) && 16 !== strlen($iv)) {
            throw new \InvalidArgumentException('IV length must be 16 bytes.');
        }
    }
}