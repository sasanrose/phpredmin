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
    protected function mockStartTransaction($result)
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
    protected function mockStartWatchTransaction($result, $keys)
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
    protected function mockCallbackTransaction($result, $callBackResult)
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
    protected function mockCommitTransaction($result)
    {
        $this->redis
            ->expects($this->once())
            ->method('exec')
            ->willReturn($result);
    }
}
