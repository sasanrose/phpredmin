<?php

/**
 * This file is part of PHPRedmin project.
 *
 * (c) Sasan Rose <sasan.rose@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PhpRedmin\Test\Phpunit\Traits;

trait Redis
{
    /**
     * Redis mock.
     *
     * @var Redis
     */
    protected $redis = NULL;

    /**
     * Mock start transaction.
     *
     * @param mixed $result
     */
    protected function mockStartTransaction($result): void
    {
        $this->redis
            ->expects($this->once())
            ->method('multi')
            ->willReturn($result);
    }

    /**
     * Mock start watch transaction.
     *
     * @param mixed $result
     * @param mixed $keys
     */
    protected function mockStartWatchTransaction($result, $keys): void
    {
        $keys = (array) $keys;

        $watch = $this->redis
            ->expects($this->once())
            ->method('watch');

        if ($keys) {
            call_user_func_array([$watch, 'with'], $keys);
        }

        $this->redis
            ->expects($this->once())
            ->method('multi')
            ->willReturn($result);
    }

    /**
     * Mock start transaction with callback.
     *
     * @param mixed $result
     * @param mixed $callBackResult
     */
    protected function mockCallbackTransaction($result, $callBackResult): void
    {
        $this->redis
            ->expects($this->once())
            ->method('watch');

        if (!$callBackResult) {
            $this->redis
                ->expects($this->once())
                ->method('unwatch');

            return;
        }

        $this->redis
            ->expects($this->once())
            ->method('multi')
            ->willReturn($result);
    }

    /**
     * Mocks the commit of a transaction.
     *
     * @param mixed $result
     */
    protected function mockCommitTransaction($result): void
    {
        $this->redis
            ->expects($this->once())
            ->method('exec')
            ->willReturn($result);
    }

    /**
     * Mocks connection to default redis server and db.
     */
    protected function mockDefaultConnect(): void
    {
        $this->container['REDIS_DEFAULT_SERVER'] = 0;
        $this->container['REDIS_DEFAULT_DB'] = 1;

        $this->container['REDIS_SERVERS'] = [
            ['ADDR' => 'redis0', 'PORT' => 63790, 'PASS' => 'alpha'],
        ];

        $this->redis
            ->expects($this->once())
            ->method('connect')
            ->with('redis0', 63790);

        $this->redis
            ->expects($this->once())
            ->method('auth')
            ->with('alpha');

        $this->redis
            ->expects($this->once())
            ->method('select')
            ->with(1);
    }
}
