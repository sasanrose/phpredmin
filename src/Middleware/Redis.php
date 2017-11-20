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
use PhpRedmin\Redis as PhpRedminRedis;
use PhpRedmin\Traits;
use Pimple\Container;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class Redis implements MiddlewareInterface
{
    use Traits\Redis;

    /**
     * Dependency injection container.
     *
     * @var Container
     */
    protected $container;

    /**
     * Redis instance to connect to Redis.
     *
     * @var PhpRedmin\Redis
     */
    protected $redis;

    /**
     * Default paths that should use default server and db.
     */
    protected $pathsWithDefault = ['/login', '/logout', '/install'];

    /**
     * Instantiates Redis middleware.
     *
     * @param Container       $container
     * @param PhpRedmin\Redis $redis
     */
    public function __construct(
        Container $container,
        PhpRedminRedis $redis
    ) {
        $this->container = $container;
        $this->redis = $redis;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(RequestInterface $request, ResponseInterface $response, callable $next): ResponseInterface
    {
        $query = [];
        $uri = $request->getUri();
        $path = $uri->getPath();
        $queryString = $uri->getQuery();

        if ($queryString) {
            parse_str($queryString, $query);
        }

        $serverIndex = $this->getRedisIndex($path, $query);
        $dbIndex = $this->getDbIndex($path, $query);

        $this->connect(
            $this->redis,
            $this->container,
            $serverIndex,
            $dbIndex
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
    protected function getRedisIndex(string $path, array $query = []): int
    {
        $defaultRedis = $this->container['REDIS_DEFAULT_SERVER'];

        if ($this->isDefaultPath($path)) {
            return $defaultRedis;
        }

        if (isset($query['redis']) && isset($this->container['REDIS_SERVERS'][$query['redis']])) {
            return $query['redis'];
        }

        return $defaultRedis;
    }

    /**
     * Return the db server that should be used.
     *
     * @param string $path
     * @param array  $query
     *
     * @return int
     */
    protected function getDbIndex(string $path, array $query = []): int
    {
        $defaultDb = $this->container['REDIS_DEFAULT_DB'];

        if ($this->isDefaultPath($path)) {
            return $defaultDb;
        }

        if (isset($query['db'])) {
            return $query['db'];
        }

        return $defaultDb;
    }

    /**
     * Checks if path in the list of default paths or not.
     *
     * @param string $path
     *
     * @return bool
     */
    protected function isDefaultPath(string $path): bool
    {
        foreach ($this->pathsWithDefault as $pathWithDefault) {
            if (preg_match('/^'.preg_quote($pathWithDefault, '/').'/', $path)) {
                return TRUE;
            }
        }

        return FALSE;
    }
}
