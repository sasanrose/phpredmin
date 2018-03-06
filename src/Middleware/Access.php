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
use PhpRedmin\Model\Group;
use PhpRedmin\Redis;
use PhpRedmin\Traits;
use PhpRedmin\Url\UrlBuilderInterface;
use Pimple\Container;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use PSR7Sessions\Storageless\Http\SessionMiddleware;

class Access implements MiddlewareInterface
{
    use Traits\Redis;

    /**
     * Group.
     *
     * @var Group
     */
    protected $model;

    /**
     * Url builder.
     *
     * @var UrlBuilderInterface
     */
    protected $urlBuilder;

    /**
     * Instantiates access middleware.
     *
     * @param Group               $model
     * @param UrlBuilderInterface $urlBuilder
     * @param Redis               $redis
     * @param Container           $container
     */
    public function __construct(
        Group $model,
        UrlBuilderInterface $urlBuilder,
        Redis $redis,
        Container $container
    ) {
        $this->model = $model;
        $this->urlBuilder = $urlBuilder;

        $this->connect(
            $redis,
            $container,
            $container['REDIS_DEFAULT_SERVER'],
            $container['REDIS_DEFAULT_DB']
        );
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next): ResponseInterface
    {
        $session = $request->getAttribute(SessionMiddleware::SESSION_ATTRIBUTE);
        if ($session
            && $session->has('email')
            && $this->model->isMember('administrators', $session->get('email'))) {
            return $next($request, $response);
        }

        $uri = $request->getUri();
        $path = $uri->getPath();

        if (preg_match('/^\/(login|install|misc\/)/', $path)) {
            return $next($request, $response);
        }

        $this->urlBuilder->setPath('misc/access-denied');

        return $response->withRedirect($this->urlBuilder->toString());
    }
}
