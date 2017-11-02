<?php

/**
 * This file is part of PHPRedmin project.
 *
 * (c) Sasan Rose <sasan.rose@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PhpRedmin\Test\Middleware;

use PhpRedmin\Middleware\Install;
use PhpRedmin\Model\Systeminfo;
use PhpRedmin\Test\Phpunit\MiddlewareTestcase;
use PhpRedmin\Url\UrlBuilderInterface;
use Pimple\Container;
use Psr\Http\Message\UriInterface;
use Redis as PhpRedis;

/**
 * @group middleware
 */
class InstallTest extends MiddlewareTestcase
{
    protected $url;
    protected $model;
    protected $container;

    public function setUp()
    {
        parent::setUp();

        $this->model = $this->createMock(Systeminfo::class);
        $this->redis = $this->createMock(PhpRedis::class);
        $this->url = $this->createMock(UrlBuilderInterface::class);
        $this->container = new Container();

        $this->container['REDIS_DEFAULT_SERVER'] = 0;
        $this->container['REDIS_DEFAULT_DB'] = 1;

        $this->container['REDIS_SERVERS'] = [
            ['ADDR' => 'redis0', 'PORT' => 63790, 'PASS' => 'alpha'],
        ];

        $this->redis
            ->expects($this->once())
            ->method('connect')
            ->with('redis0', 63790);

        $this->redis
            ->expects($this->once())
            ->method('auth')
            ->with('alpha');

        $this->redis
            ->expects($this->once())
            ->method('select')
            ->with(1);
    }

    public function testInstalled()
    {
        $this->model
            ->expects($this->once())
            ->method('isInstalled')
            ->willReturn(TRUE);

        $this->response
            ->expects($this->never())
            ->method('withRedirect');

        $middleware = new Install(
            $this->model,
            $this->url,
            $this->redis,
            $this->container
        );
        $middleware($this->request, $this->response, $this->next);
    }

    public function testNotInstalled()
    {
        $this->model
            ->expects($this->once())
            ->method('isInstalled')
            ->willReturn(FALSE);

        $uri = $this->createMock(UriInterface::class);
        $uri
            ->expects($this->once())
            ->method('getPath')
            ->willReturn('path');

        $this->request
            ->expects($this->once())
            ->method('getUri')
            ->willReturn($uri);

        $this->url
            ->expects($this->once())
            ->method('setPath')
            ->with('install');

        $this->url
            ->expects($this->once())
            ->method('toString')
            ->willReturn('test-uri');

        $this->response
            ->expects($this->once())
            ->method('withRedirect')
            ->with('test-uri');

        $middleware = new Install(
            $this->model,
            $this->url,
            $this->redis,
            $this->container
        );
        $middleware($this->request, $this->response, $this->next);
    }

    public function testNotInstalledInstallPath()
    {
        $this->model
            ->expects($this->once())
            ->method('isInstalled')
            ->willReturn(FALSE);

        $uri = $this->createMock(UriInterface::class);
        $uri
            ->expects($this->once())
            ->method('getPath')
            ->willReturn('/install');

        $this->request
            ->expects($this->once())
            ->method('getUri')
            ->willReturn($uri);

        $this->response
            ->expects($this->never())
            ->method('withRedirect');

        $middleware = new Install(
            $this->model,
            $this->url,
            $this->redis,
            $this->container
        );
        $middleware($this->request, $this->response, $this->next);
    }
}
