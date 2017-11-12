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

use PhpRedmin\Middleware\Auth;
use PhpRedmin\Test\Phpunit\MiddlewareTestCase;
use PhpRedmin\Url\UrlBuilderInterface;
use Psr\Http\Message\UriInterface;
use PSR7Sessions\Storageless\Http\SessionMiddleware;
use PSR7Sessions\Storageless\Session\SessionInterface;

/**
 * @group middleware
 */
class AuthTest extends MiddlewareTestCase
{
    protected $session;
    protected $url;

    public function setUp()
    {
        parent::setUp();

        $this->url = $this->createMock(UrlBuilderInterface::class);
        $this->session = $this->createMock(SessionInterface::class);

        $this->request
            ->expects($this->once())
            ->method('getAttribute')
            ->with(SessionMiddleware::SESSION_ATTRIBUTE)
            ->willReturn($this->session);
    }

    public function testLoggedin()
    {
        $this->session
            ->expects($this->once())
            ->method('has')
            ->with('email')
            ->willReturn(TRUE);

        $this->response
            ->expects($this->never())
            ->method('withRedirect');

        $middleware = new Auth(
            $this->url
        );

        $middleware($this->request, $this->response, $this->next);
    }

    public function testNotLoggedIn()
    {
        $this->session
            ->expects($this->once())
            ->method('has')
            ->with('email')
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
            ->with('login');

        $this->url
            ->expects($this->once())
            ->method('toString')
            ->willReturn('test-uri');

        $this->response
            ->expects($this->once())
            ->method('withRedirect')
            ->with('test-uri');

        $middleware = new Auth(
            $this->url
        );

        $middleware($this->request, $this->response, $this->next);
    }

    public function testNotLoggedInLoginPath()
    {
        $this->notLoggedIn('/login');
    }

    public function testNotLoggedInInstallPath()
    {
        $this->notLoggedIn('/install');
    }

    protected function notLoggedIn($path)
    {
        $this->session
            ->expects($this->once())
            ->method('has')
            ->with('email')
            ->willReturn(FALSE);

        $uri = $this->createMock(UriInterface::class);
        $uri
            ->expects($this->once())
            ->method('getPath')
            ->willReturn($path);

        $this->request
            ->expects($this->once())
            ->method('getUri')
            ->willReturn($uri);

        $this->response
            ->expects($this->never())
            ->method('withRedirect');

        $middleware = new Auth(
            $this->url
        );

        $middleware($this->request, $this->response, $this->next);
    }
}
