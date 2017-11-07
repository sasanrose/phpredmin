<?php

/**
 * This file is part of PHPRedmin project.
 *
 * (c) Sasan Rose <sasan.rose@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PhpRedmin\Traits;

use Pimple\Container;
use Redis as PhpRedis;

trait Redis
{
    /**
     * Global prefix for the PhpRedmin keys.
     *
     * @var string
     */
    protected $globalPrefix = 'PHPREDMIN';

    /**
     * If we are inside a redis multi transaction or not.
     *
     * @var bool
     */
    protected $redisTransaction = FALSE;

    /**
     * Generates a key to be used in redis.
     *
     * @param string|array $prefix
     * @param string       $key
     *
     * @return string
     */
    protected function prepareKey($prefix, string $key = NULL) : string
    {
        $prefix = (array) $prefix;

        $prefix = implode(':', $prefix);

        $prefix = $this->globalPrefix.':'.$prefix;

        if (!isset($key)) {
            return $prefix;
        }

        return $prefix.':'.hash('sha256', $key);
    }

    /**
     * Start a redis transaction.
     *
     * @param Redis    $redis      Redis instance
     * @param mixed    $keys       List of keys to watch for
     * @param callable $watchCheck A callable function that will check the
     *                             list of keys to make sure it is not changed
     *                             since we started watching
     *
     * @return bool
     */
    protected function startTransaction(PhpRedis $redis, $keys = [], callable $watchCheck = NULL) : bool
    {
        $keys = (array) $keys;

        if (isset($keys)) {
            call_user_func_array([$redis, 'watch'], $keys);

            if (isset($watchCheck)) {
                $result = call_user_func($watchCheck, $redis, $keys);

                if (FALSE === $result) {
                    call_user_func_array([$redis, 'unwatch'], $keys);

                    return FALSE;
                }
            }
        }

        $this->redisTransaction = TRUE;

        return FALSE !== $redis->multi();
    }

    /**
     * Commits a transaction.
     *
     * @param Redis $redis Redis instance
     *
     * @return mixed
     */
    protected function commitTransaction(PhpRedis $redis)
    {
        if (!$this->redisTransaction) {
            throw new \Exception('No redis transaction has started yet');
        }

        $result = $redis->exec();

        $this->redisTransaction = FALSE;

        return $result;
    }

    /**
     * Connects to a redis server and selects a db.
     *
     * @param Redis     $redis
     * @param Container $container
     * @param int       $serverIndex
     * @param int       $dbIndex
     */
    protected function connect(
        PhpRedis $redis,
        Container $container,
        int $serverIndex,
        int $dbIndex
    ) : void {
        $redis->connect(
            $container['REDIS_SERVERS'][$serverIndex]['ADDR'],
            $container['REDIS_SERVERS'][$serverIndex]['PORT']
        );

        if (isset($container['REDIS_SERVERS'][$serverIndex]['PASS'])) {
            $redis->auth($container['REDIS_SERVERS'][$serverIndex]['PASS']);
        }

        $redis->select($dbIndex);
    }
}
