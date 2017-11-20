<?php

/**
 * This file is part of PHPRedmin project.
 *
 * (c) Sasan Rose <sasan.rose@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PhpRedmin\Test;

use PhpRedmin\Redis;
use PHPUnit\Framework\TestCase;

class RedisTest extends TestCase
{
    public function setUp()
    {
        $this->redis = new Redis();
    }

    public function testTransaction()
    {
        $this->assertFalse($this->redis->isTransactionStrated());

        $this->redis->startTransaction();

        $this->assertTrue($this->redis->isTransactionStrated());

        $this->redis->commitTransaction();

        $this->assertFalse($this->redis->isTransactionStrated());
    }

    public function testRedisServerIndex()
    {
        $this->redis->setRedisServerIndex(1);

        $this->assertEquals(1, $this->redis->getRedisServerIndex());
    }

    public function testDbIndex()
    {
        $this->redis->setDbIndex(1);

        $this->assertEquals(1, $this->redis->getDbIndex());
    }
}
