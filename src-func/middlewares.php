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

use PhpRedmin\Url\UrlBuilderInterface;
use Pimple\Container;
use PSR7Sessions\Storageless\Http\SessionMiddleware;

function middlewares(Container $c)
{
    $c[Middleware\Access::class] = function ($c) {
        return new Middleware\Access(
            $c[Model\Group::class],
            $c[UrlBuilderInterface::class],
            $c[Redis::class],
            $c
        );
    };

    $c[Middleware\Auth::class] = function ($c) {
        return new Middleware\Auth(
            $c[UrlBuilderInterface::class]
        );
    };

    $c[Middleware\Install::class] = function ($c) {
        return new Middleware\Install(
            $c[Model\Systeminfo::class],
            $c[UrlBuilderInterface::class],
            $c[Redis::class],
            $c
        );
    };

    $c[Middleware\Redis::class] = function ($c) {
        return new Middleware\Redis($c, $c[Redis::class]);
    };

    $c[SessionMiddleware::class] = function ($c) {
        return SessionMiddleware::fromSymmetricKeyDefaults(
            $c['SESSION_KEY'],
            $c['SESSION_LIFETIME']
        );
    };

    return $c;
}
