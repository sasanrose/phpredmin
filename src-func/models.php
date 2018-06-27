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

use Pimple\Container;

function models(Container $c)
{
    $c[Model\Systeminfo::class] = function ($c) {
        return new Model\Systeminfo(
            $c[Model\User::class],
            $c[Model\Group::class],
            $c[Redis::class]
        );
    };

    $c[Model\User::class] = function ($c) {
        return new Model\User(
            $c[Redis::class]
        );
    };

    $c[Model\Group::class] = function ($c) {
        return new Model\Group(
            $c[Redis::class]
        );
    };

    $c[Model\Auth::class] = function ($c) {
        return new Model\Auth(
            $c[Redis::class]
        );
    };

    $c[Model\Key::class] = function ($c) {
        return new Model\Key(
            $c[Redis::class]
        );
    };

    return $c;
}
