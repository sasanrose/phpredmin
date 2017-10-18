<?php

/**
 * This file is part of PHPRedmin project.
 *
 * (c) Sasan Rose <sasan.rose@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

require_once implode(DIRECTORY_SEPARATOR, [dirname(__DIR__), 'vendor', 'autoload.php']);

$container = new \Pimple\Container();

$container = \PhpRedmin\envs($container);
$container = \PhpRedmin\i18n($container);
$container = \PhpRedmin\logger($container);
$container = \PhpRedmin\dependencies($container);
$container = \PhpRedmin\models($container);
$container = \PhpRedmin\controllers($container);
$container = \PhpRedmin\routing($container);
//$container = \PhpRedmin\middlewares($container);

$response = $container[\League\Route\RouteCollectionInterface::class]->dispatch(
    $container[\Psr\Http\Message\ServerRequestInterface::class],
    $container[\Psr\Http\Message\ResponseInterface::class]
);

$container[\Zend\Diactoros\Response\EmitterInterface::class]->emit($response);
