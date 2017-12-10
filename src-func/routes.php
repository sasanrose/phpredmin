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

use League\Route\RouteCollectionInterface;
use Pimple\Container;

function routing(Container $c)
{
    $routeCollection = $c[RouteCollectionInterface::class];

    $routeCollection->get('/install', [
        $c[Controller\InstallerInterface::class],
        'install',
    ]);

    $routeCollection->post('/install', [
        $c[Controller\InstallerInterface::class],
        'doInstall',
    ]);

    $routeCollection->get('/login', [
        $c[Controller\AuthInterface::class],
        'login',
    ]);

    $routeCollection->post('/login', [
        $c[Controller\AuthInterface::class],
        'doLogin',
    ]);

    $routeCollection->get('/misc/access-denied', [
        $c[Controller\MiscInterface::class],
        'accessDenied',
    ]);

    $routeCollection->get('/', [
        $c[Controller\MiscInterface::class],
        'main',
    ]);

    return $c;
}
