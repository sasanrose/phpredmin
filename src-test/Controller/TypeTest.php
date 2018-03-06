<?php

/**
 * This file is part of PHPRedmin project.
 *
 * (c) Sasan Rose <sasan.rose@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PhpRedmin\Test\Controller;

use PhpRedmin\Controller\Keys as KeysController;
use PhpRedmin\Redis;
use PhpRedmin\Test\Phpunit\ControllerTestCase;
use PhpRedmin\Url\UrlBuilderInterface;

/**
 * @group controller
 */
class TypeTest extends ControllerTestCase
{
    protected $redis;
    protected $urlBuilder;

    public function setUp()
    {
        parent::setUp();

        $this->redis = $this->createMock(Redis::class);
        $this->urlBuilder = $this->createMock(UrlBuilderInterface::class);
    }

    public function testEmptyKey()
    {
        $this->request
            ->expects($this->once())
            ->method('getAttributes')
            ->willReturn(['action' => 'type', 'keys' => []]);

        $errors['key'] = 'Key is required';

        $this->mockResponse('controller/keys/key.twig', ['errors' => $errors]);

        $keys = $this->getController();
        $keys->search($this->request, $this->response);
    }

    public function testKeyNotFound()
    {
        $this->request
            ->expects($this->once())
            ->method('getAttributes')
            ->willReturn(['action' => 'type', 'keys' => ['key']]);

        $this->redis
            ->expects($this->once())
            ->method('type')
            ->with('key')
            ->willReturn(Redis::REDIS_NOT_FOUND);

        $this->mockResponse('controller/keys/not-found.twig', ['search' => 'key']);

        $keys = $this->getController();
        $keys->search($this->request, $this->response);
    }

    public function testUnknownType()
    {
        $this->request
            ->expects($this->once())
            ->method('getAttributes')
            ->willReturn(['action' => 'type', 'keys' => ['key']]);

        $this->redis
            ->expects($this->once())
            ->method('type')
            ->with('key')
            ->willReturn('unknown type');

        $this->mockResponse('controller/keys/unknown-type.twig', ['search' => 'key']);

        $keys = $this->getController();
        $keys->search($this->request, $this->response);
    }

    /**
     * @SuppressWarnings(ExcessiveMethodLength)
     */
    public function testKey()
    {
        $keyTypes = [
            Redis::REDIS_STRING => 'get',
            Redis::REDIS_SET => 'smembers',
            Redis::REDIS_LIST => 'range',
            Redis::REDIS_ZSET => 'zrange',
            Redis::REDIS_HASH => 'hgetall',
        ];

        $queries = [];
        $redisQueries = [];

        foreach ($keyTypes as $keyType => $action) {
            $redisQueries[] = [
                'redis' => 0,
                'db' => 1,
                'action' => $action,
                'key' => ['key'],
            ];

            $queries[] = [
                ['keyType' => $keyType],
            ];
        }

        $count = count($keyTypes);

        $this->request
            ->expects($this->exactly($count))
            ->method('getAttributes')
            ->willReturn(['action' => 'type', 'keys' => ['key']]);

        $this->redis
            ->expects($this->exactly($count))
            ->method('getServerIndex')
            ->willReturn(0);

        $this->redis
            ->expects($this->exactly($count))
            ->method('getDbIndex')
            ->willReturn(1);

        $this->redis
            ->expects($this->exactly($count))
            ->method('type')
            ->with('key')
            ->will($this->onConsecutiveCalls(...array_keys($keyTypes)));

        $this->urlBuilder
            ->expects($this->exactly($count))
            ->method('setRedis')
            ->withConsecutive(...$redisQueries)
            ->willReturn($this->urlBuilder);

        $this->urlBuilder
            ->expects($this->exactly($count))
            ->method('setQuery')
            ->withConsecutive(...$queries)
            ->willReturn($this->urlBuilder);

        $this->urlBuilder
            ->expects($this->exactly($count))
            ->method('setPath')
            ->with('view')
            ->willReturn($this->urlBuilder);

        $this->urlBuilder
            ->expects($this->exactly($count))
            ->method('toString')
            ->willReturn('test-url');

        $this->response
            ->expects($this->exactly($count))
            ->method('withRedirect')
            ->with('test-url')
            ->willReturn($this->response);

        foreach ($keyTypes as $action) {
            $keys = $this->getController();
            $keys->search($this->request, $this->response);
        }
    }

    protected function getController()
    {
        return new KeysController(
            $this->twig,
            $this->redis,
            $this->urlBuilder,
            $this->logger
        );
    }
}
