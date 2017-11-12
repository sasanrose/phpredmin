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

use League\Route;
use PhpRedmin\Integration\Twig\Extension\GlobalVars;
use PhpRedmin\Integration\Zend\Diactoros\Response;
use PhpRedmin\Url\Builder\Pecl as PeclUrlBuilder;
use PhpRedmin\Url\UrlBuilderInterface;
use PhpRedmin\Validator\FormValidator;
use PhpRedmin\Validator\FormValidatorInterface;
use Pimple\Container;
use Pimple\Psr11;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use PSR7Sessions\Storageless\Http\SessionMiddleware;
use Redis;
use Twig\Environment;
use Twig\Extensions\I18nExtension;
use Twig\Loader\FilesystemLoader;
use Zend\Diactoros\Response\EmitterInterface;
use Zend\Diactoros\Response\SapiEmitter;
use Zend\Diactoros\ServerRequestFactory;

/**
 * @SuppressWarnings(ExcessiveMethodLength)
 * @SuppressWarnings(StaticAccess)
 * @SuppressWarnings(Superglobals)
 */
function dependencies(Container $c)
{
    $c[Psr11\Container::class] = $c->factory(function ($c) {
        return new Psr11\Container($c);
    });

    $c[Redis::class] = function ($c) {
        $redis = new Redis();

        return $redis;
    };

    $c[Route\RouteCollectionInterface::class] = function ($c) {
        $router = new Route\RouteCollection($c[Psr11\Container::class]);

        $router->middleware($c[Middleware\Redis::class]);
        $router->middleware($c[Middleware\Access::class]);
        $router->middleware($c[Middleware\Auth::class]);
        $router->middleware($c[Middleware\Install::class]);
        $router->middleware($c[SessionMiddleware::class]);

        return $router;
    };

    $c[ResponseInterface::class] = function ($c) {
        return new Response();
    };

    $c[ServerRequestInterface::class] = $c->factory(function ($c) {
        return ServerRequestFactory::fromGlobals(
            $_SERVER,
            $_GET,
            $_POST,
            $_COOKIE,
            $_FILES
        );
    });

    $c[EmitterInterface::class] = function ($c) {
        return new SapiEmitter();
    };

    $c[Environment::class] = function ($c) {
        $loader = new FilesystemLoader($c['TEMPLATES_DIR']);

        $options = [];

        $options['cache'] = $c['TEMPLATES_CACHE_DIR'];

        $c['DEVELOPMENT_MODE'] && $options['auto_reload'] = TRUE;

        $twig = new Environment($loader, $options);

        $globalVars = [];

        isset($c['UI_LANG']) && $globalVars['lang'] = $c['UI_LANG'];
        isset($c['UI_LANG_DIR']) && $globalVars['dir'] = $c['UI_LANG_DIR'];

        $twig->addExtension(new GlobalVars($globalVars));

        $twig->addExtension(new I18nExtension());

        return $twig;
    };

    $c[UrlBuilderInterface::class] = $c->factory(function ($c) {
        $builder = new PeclUrlBuilder();

        $builder->setHost($_SERVER['HTTP_HOST']);
        $builder->setScheme($_SERVER['REQUEST_SCHEME']);

        return $builder;
    });

    $c[FormValidatorInterface::class] = $c->factory(function ($c) {
        return new FormValidator();
    });

    return $c;
}
