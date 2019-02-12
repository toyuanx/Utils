<?php
/**
 * redis锁
 *
 * User: 原子酱
 * Date: 2019/2/12
 * Time: 15:15
 */

class redisLock
{
    private static $lockExpire = 30; //锁过期时间

    /**
     * 加锁
     *
     * @param $key
     * @param null $expire
     * @return bool
     */
    public static function lock($key, $expire = null)
    {
        $expire = empty($expire) ? self::$lockExpire : $expire;

        /**
         * @var \Redis $redis
         */
        $redis = getDI()->getShared('redis');

        return $redis->set($key, 'true', array('nx', 'ex' => $expire));
    }

    /**
     * 解锁
     *
     * @param $key
     * @return int
     */
    public static function unlock($key)
    {
        /**
         * @var \Redis $redis
         */
        $redis = getDI()->getShared('redis');
        return $redis->del($key);
    }
}