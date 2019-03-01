<?php
/**
 * 常用helpers方法，可用作composer.json自动加载文件
 *
 * User: 原子酱
 * Date: 2019/3/1
 * Time: 14:36
 */

/**
 * 签名
 *
 * @param array $params
 * @param $key
 * @param string $encryptMethod
 * @return string
 */
function sign(array $params, $key, $encryptMethod = 'md5')
{
    ksort($params);

    $params['key'] = $key;

    return strtoupper(call_user_func($encryptMethod, [urldecode(http_build_query($params))]));
}