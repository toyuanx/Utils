<?php
/**
 * 脚本参数处理方法
 *
 * User: 原子酱
 * Date: 2019/2/12
 * Time: 15:44
 */

class parseArgs
{

    /**
     * 获取参数，参数格式为：--arg1={$arg1} --arg2={$arg2} ...
     *
     * @param array $args
     * @return array
     */
    public static function get($args = [])
    {
        if (empty($args))
            return [];

        $params = [];
        foreach ($args as $arg) {
            $item = explode("=", $arg);
            if (!is_array($item))
                continue;
            $item[0] = isset($item[0]) ? trim($item[0]) : '';
            $item[1] = isset($item[1]) ? trim($item[1]) : '';

            $_key = str_replace('-', '', $item[0]);
            $_val = strpos($item[1], ',') !== false ? explode(',', $item[1]) : $item[1];
            $params[$_key] = $_val;
        }

        return $params;
    }

}