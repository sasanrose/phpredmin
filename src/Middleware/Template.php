<?php

/**
 * This file is part of PHPRedmin project.
 *
 * (c) Sasan Rose <sasan.rose@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PhpRedmin\Middleware;

use PhpRedmin\MiddlewareInterface;
use PhpRedmin\Model\User;
use PhpRedmin\Redis;
use PhpRedmin\Traits;
use Pimple\Container;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use PSR7Sessions\Storageless\Http\SessionMiddleware;
use Twig\Environment;

class Template implements MiddlewareInterface
{
    use Traits\Redis;

    /**
     * Container.
     *
     * @var Container
     */
    protected $container;

    /**
     * User model.
     *
     * @var User
     */
    protected $model;

    /**
     * Redis instance to connect to Redis.
     *
     * @var Redis
     */
    protected $redis;

    /**
     * Twig Environment.
     *
     * @var Twig\Environment
     */
    protected $twig;

    /**
     * Instantiates template Middleware.
     *
     * @param Container   $container
     * @param Redis       $redis
     * @param User        $model
     * @param Environment $twig
     */
    public function __construct(
        Container $container,
        Redis $redis,
        User $model,
        Environment $twig
    ) {
        $this->container = $container;
        $this->redis = $redis;
        $this->model = $model;
        $this->twig = $twig;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(RequestInterface $request, ResponseInterface $response, callable $next): ResponseInterface
    {
        $session = $request->getAttribute(SessionMiddleware::SESSION_ATTRIBUTE);

        if ($session && $session->has('email')) {
            $user = $this->model->get($session->get('email'));

            $this->twig->addGlobal('user', $user);
        }

        $this->twig->addGlobal('serverIndex', $this->redis->getServerIndex());
        $this->twig->addGlobal('dbIndex', $this->redis->getDbIndex());
        $this->twig->addGlobal('servers', $this->container['REDIS_SERVERS']);
        $this->twig->addGlobal('dbs', $this->getDatabases($this->redis));

        return $next($request, $response);
    }
}
