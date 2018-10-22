<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/10/11
 * Time: 11:31
 */
namespace common\components\wechat\src\config;
class Config{
    //获取session信息
    const URL_TO_SESSION = 'https://api.weixin.qq.com/sns/jscode2session';
    //获取token信息
    const URL_TOKEN_GET = 'https://api.weixin.qq.com/cgi-bin/token';
    //获取不受限制的小程序码
    const URL_UN_LIMIT_CREATE = 'https://api.weixin.qq.com/wxa/getwxacodeunlimit';
    //获取永久的小程序码
    const URL_CREATE = 'https://api.weixin.qq.com/wxa/getwxacode';

    //状态信息
    const ERROR_NO_RESPONSE = '本次请求并没有得到响应，请检查通讯是否畅通。';
}