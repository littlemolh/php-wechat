<?php

namespace littlemo\wechat\gzh;

use littlemo\utils\HttpClient;
use littlemo\utils\Common;
use littlemo\wechat\Base;

/**
 * TODO 微信网页JS-SDK开发
 *
 * @author sxd
 * @Date 2019-07-25 10:43
 */
class Jsapi extends Base
{

    /**
     * 获得jsapi_ticket
     * 
     * 文档：https://developers.weixin.qq.com/doc/offiaccount/OA_Web_Apps/JS-SDK.html#62
     *
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2021-11-04
     * @version 2021-11-04
     * @param string $access_token
     * @return array
     */
    public function ticket($access_token)
    {

        $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket";
        $params = [
            "access_token" =>  $access_token,
            "type" =>  'jsapi',
        ];
        return $this->init_result((new HttpClient())->get($url, $params));
    }

    /**
     * JS-SDK使用权限签名算法
     *
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2021-11-04
     * @version 2021-11-04
     * @param string $noncestr      随机字符串
     * @param string $jsapi_ticket
     * @param int    $timestamp     时间戳
     * @param string $url           当前网页的URL，不包含#及其后面部分
     * @return void
     */
    public function signature($jsapi_ticket = '', &$noncestr = '',  &$timestamp = '', &$url = '')
    {
        $noncestr = $noncestr ?: Common::createNonceStr();
        $timestamp = $timestamp ?: time();
        $url = $url ?: ($_SERVER['HTTP_REFERER'] ?? '');
        $params = [
            'noncestr' => $noncestr,
            'jsapi_ticket' => $jsapi_ticket,
            'timestamp' => $timestamp,
            'url' => $url,
        ];
        return Common::createSign($params, [], 'sha1');
    }
}
