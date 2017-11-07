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
use PhpRedmin\Model\Systeminfo;
use PhpRedmin\Traits;
use PhpRedmin\Url\UrlBuilderInterface;
use Pimple\Container;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Redis;

class Install implements MiddlewareInterface
{
    use Traits\Redis;

    /**
     * System info model.
     *
     * @var Systeminfo
     */
    protected $model;

    /**
     * Url builder.
     *
     * @var UrlBuilderInterface
     */
    protected $urlBuilder;

    /**
     * Instantiates Install middleware.
     *
     * @param Systeminfo          $model
     * @param UrlBuilderInterface $urlBuilder
     * @param Redis               $redis
     * @param int                 $defaultServerIndex
     * @param int                 $defaultDbIndex
     */
    public function __construct(
        Systeminfo $model,
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
    public function __invoke(RequestInterface $request, ResponseInterface $response, callable $next) : ResponseInterface
    {
        if (!$this->model->isInstalled()) {
            $uri = $request->getUri();
            $path = $uri->getPath();

            if (!preg_match('/^\/install/', $path)) {
                $this->urlBuilder->setPath('install');

                return $response->withRedirect($this->urlBuilder->toString());
            }
        }

        return $next($request, $response);
    }
}
