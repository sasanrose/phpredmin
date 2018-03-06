<?php

/**
 * This file is part of PHPRedmin project.
 *
 * (c) Sasan Rose <sasan.rose@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PhpRedmin\Controller\Traits\Keys;

use PhpRedmin\Redis;

trait Actions
{
    protected $actions = [
        Redis::REDIS_STRING => 'get',
        Redis::REDIS_SET => 'smembers',
        Redis::REDIS_LIST => 'range',
        Redis::REDIS_ZSET => 'zrange',
        Redis::REDIS_HASH => 'hgetall',
    ];
}
