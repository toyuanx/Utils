<?php
/**
 * redis锁实践
 *
 * User: 原子酱
 * Date: 2019/2/22
 * Time: 14:47
 */

class redisLockTest
{
    public function test()
    {
        $lockKey = 'test';

        try {
            // 加锁
            if (!redisLock::lock($lockKey)) {
                throw new \Exception('locked!');
            }

            // todo: 这里写业务逻辑
            //
            //
            //
        } catch (\Exception $exception) {
            throw $exception;

        } finally {

            // 删除锁
            redisLock::unlock($lockKey);
        }
    }

}