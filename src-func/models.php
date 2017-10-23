<?php

/**
 * This file is part of PHPRedmin project.
 *
 * (c) Sasan Rose <sasan.rose@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PhpRedmin;

use PhpRedmin\Model\Group;
use PhpRedmin\Model\Systeminfo;
use PhpRedmin\Model\User;
use Pimple\Container;
use Redis;

function models(Container $c)
{
    $c[Systeminfo::class] = function ($c) {
        return new Systeminfo(
            $c[User::class],
            $c[Group::class],
            $c[Redis::class]
        );
    };

    $c[User::class] = function ($c) {
        return new User(
            $c[Redis::class]
        );
    };

    $c[Group::class] = function ($c) {
        return new Group(
            $c[Redis::class]
        );
    };

    return $c;
}
