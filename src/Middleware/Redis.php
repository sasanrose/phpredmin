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
use Pimple\Container;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Redis as PhpRedis;

class Redis implements MiddlewareInterface
{
    /**
     * Dependency injection container.
     *
     * @var Container
     */
    protected $container;

    /**
     * Redis instance to connect to Redis.
     *
     * @var PhpRedis
     */
    protected $redis;

    /**
     * Instantiates Redis middleware.
     *
     * @param Container $container
     * @param Redis     $redis
     */
    public function __construct(
        Container $container,
        PhpRedis $redis
    ) {
        $this->container = $container;
        $this->redis = $redis;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(RequestInterface $request, ResponseInterface $response, callable $next)
    {
        $query = [];
        $uri = $request->getUri();
        $path = $uri->getPath();
        $queryString = $uri->getQuery();

        if ($queryString) {
            parse_str($queryString, $query);
        }

        $redisIndex = $this->getRedisIndex($path, $query);

        $this->redis->connect(
            $this->container['REDIS_SERVERS'][$redisIndex]['ADDR'],
            $this->container['REDIS_SERVERS'][$redisIndex]['PORT']
        );

        return $next($request, $response);
    }

    /**
     * Return the redis server that should be used.
     *
     * @param string $path
     * @param array  $query
     *
     * @return int
     */
    protected function getRedisIndex(string $path, array $query = [])
    {
        $pathsWithDefault = ['/login', '/logout', '/install'];

        $defaultRedis = $this->container['REDIS_DEFAULT_SERVER'];

        foreach ($pathsWithDefault as $pathWithDefault) {
            if (preg_match('/^'.preg_quote($pathWithDefault, '/').'/', $path)) {
                return $defaultRedis;
            }
        }

        if (isset($query['redis']) && isset($this->container['REDIS_SERVERS'][$query['redis']])) {
            return $query['redis'];
        }

        return $defaultRedis;
    }
}
