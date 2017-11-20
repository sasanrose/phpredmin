<?php

/**
 * This file is part of PHPRedmin project.
 *
 * (c) Sasan Rose <sasan.rose@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PhpRedmin\Test\Middleware;

use PhpRedmin\Middleware\Redis as RedisMiddleware;
use PhpRedmin\Redis;
use PhpRedmin\Test\Phpunit\MiddlewareTestCase;
use Pimple\Container;
use Psr\Http\Message\UriInterface;

/**
 * @group middleware
 */
class RedisTest extends MiddlewareTestCase
{
    protected $container;
    protected $redis;

    public function setUp()
    {
        parent::setUp();

        $this->redis = $this->createMock(Redis::class);
        $this->container = new Container();

        $this->container['REDIS_DEFAULT_SERVER'] = 1;
        $this->container['REDIS_DEFAULT_DB'] = 0;

        $this->container['REDIS_SERVERS'] = [
            ['ADDR' => 'redis0', 'PORT' => 63790, 'PASS' => 'alpha'],
            ['ADDR' => 'redis1', 'PORT' => 63791],
        ];
    }

    public function testPath()
    {
        $paths = ['/install', '/login', '/logout'];

        $count = count($paths);

        $url = $this->createMock(UriInterface::class);
        $url
            ->expects($this->exactly($count))
            ->method('getQuery');

        $consecutive = call_user_func_array([$this, 'onConsecutiveCalls'], $paths);
        $url
            ->expects($this->exactly($count))
            ->method('getPath')
            ->will($consecutive);

        $this->request
            ->expects($this->exactly($count))
            ->method('getUri')
            ->willReturn($url);

        $redisIndex = $this->container['REDIS_DEFAULT_SERVER'];

        $this->redis
            ->expects($this->exactly($count))
            ->method('connect')
            ->with(
                $this->container['REDIS_SERVERS'][$redisIndex]['ADDR'],
                $this->container['REDIS_SERVERS'][$redisIndex]['PORT']
            );

        $this->redis
            ->expects($this->exactly($count))
            ->method('select');

        for ($i = 0; $i < $count; ++$i) {
            $middleware = new RedisMiddleware($this->container, $this->redis);
            $middleware($this->request, $this->response, $this->next);
        }
    }

    public function testQuery()
    {
        $this->query(0, 0, 1);

        $middleware = new RedisMiddleware($this->container, $this->redis);
        $middleware($this->request, $this->response, $this->next);
    }

    public function testQueryNotExist()
    {
        $this->query(2, $this->container['REDIS_DEFAULT_SERVER']);

        $middleware = new RedisMiddleware($this->container, $this->redis);
        $middleware($this->request, $this->response, $this->next);
    }

    protected function query($queryIndex, $expectedIndex, $db = NULL)
    {
        $queryString = "redis={$queryIndex}";

        if (isset($db)) {
            $this->redis
                ->expects($this->once())
                ->method('select')
                ->with($db);

            $queryString .= "&db={$db}";
        }

        $url = $this->createMock(UriInterface::class);
        $url
            ->expects($this->once())
            ->method('getQuery')
            ->willReturn($queryString);

        $url
            ->expects($this->once())
            ->method('getPath')
            ->willReturn('path');

        $this->request
            ->expects($this->once())
            ->method('getUri')
            ->willReturn($url);

        $this->redis
            ->expects($this->once())
            ->method('connect')
            ->with(
                $this->container['REDIS_SERVERS'][$expectedIndex]['ADDR'],
                $this->container['REDIS_SERVERS'][$expectedIndex]['PORT']
            );
    }
}
