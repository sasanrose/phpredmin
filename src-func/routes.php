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
use PhpRedmin\Controller\InstallerInterface;
use Pimple\Container;

function routing(Container $c)
{
    $routeCollection = $c[RouteCollectionInterface::class];

    $routeCollection->get('/install', [
        $c[InstallerInterface::class],
        'install',
    ]);

    $routeCollection->post('/install', [
        $c[InstallerInterface::class],
        'doInstall',
    ]);

    return $c;
}
