<?php

/**
 * This file is part of PHPRedmin project.
 *
 * (c) Sasan Rose <sasan.rose@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PhpRedmin\Test\Traits;

use PhpRedmin\Traits;
use PHPUnit\Framework\TestCase;
use Pimple\Container;
use Redis as PhpRedis;

class RedisTest extends TestCase
{
    use Traits\Redis;

    public function testPrepareKeyString()
    {
        $expeceted = 'PHPREDMIN:prefix:9f86d081884c7d659a2feaa0c55ad015a3bf4f1b2b0b822cd15d6c15b0f00a08';
        $got = $this->prepareKey('prefix', 'test');

        $this->assertEquals($expeceted, $got);
    }

    public function testPrepareKey()
    {
        $expeceted = 'PHPREDMIN:prefix1:prefix2:9f86d081884c7d659a2feaa0c55ad015a3bf4f1b2b0b822cd15d6c15b0f00a08';
        $got = $this->prepareKey(['prefix1', 'prefix2'], 'test');

        $this->assertEquals($expeceted, $got);
    }

    public function testPrepareWithoutKey()
    {
        $expeceted = 'PHPREDMIN:prefix';
        $got = $this->prepareKey('prefix');

        $this->assertEquals($expeceted, $got);
    }

    public function testStartTransaction()
    {
        $redis = $this->createMock(PhpRedis::class);

        $redis
            ->expects($this->once())
            ->method('watch')
            ->with('key1', 'key2');

        $redis
            ->expects($this->once())
            ->method('multi');

        $this->startTransaction($redis, ['key1', 'key2']);

        $this->assertTrue($this->redisTransaction);
    }

    public function testFailedStartTransaction()
    {
        $redis = $this->createMock(PhpRedis::class);

        $redis
            ->expects($this->once())
            ->method('watch')
            ->with('key1', 'key2');

        $redis
            ->expects($this->once())
            ->method('unwatch')
            ->with('key1', 'key2');

        $callback = function (PhpRedis $redis, $keys) {
            $this->assertEquals(['key1', 'key2'], $keys);

            return FALSE;
        };

        $result = $this->startTransaction($redis, ['key1', 'key2'], $callback);

        $this->assertFalse($result);
    }

    public function testCommitTransaction()
    {
        $redis = $this->createMock(PhpRedis::class);

        $this->redisTransaction = TRUE;

        $redis
            ->expects($this->once())
            ->method('exec');

        $this->commitTransaction($redis);
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage No redis transaction has started yet
     */
    public function testNoCommitTransaction()
    {
        $redis = $this->createMock(PhpRedis::class);

        $this->redisTransaction = FALSE;

        $redis
            ->expects($this->never())
            ->method('exec');

        $this->commitTransaction($redis);
    }

    public function testConnect()
    {
        $redis = $this->createMock(PhpRedis::class);
        $container = new Container();

        $container['REDIS_SERVERS'] = [
            ['ADDR' => 'redis0', 'PORT' => 63790, 'PASS' => 'alpha'],
        ];

        $redis
            ->expects($this->once())
            ->method('connect')
            ->with('redis0', 63790);

        $redis
            ->expects($this->once())
            ->method('auth')
            ->with('alpha');

        $redis
            ->expects($this->once())
            ->method('select')
            ->with(1);

        $this->connect($redis, $container, 0, 1);
    }
}
