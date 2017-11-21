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

use PhpRedmin\Middleware\Template;
use PhpRedmin\Model\User;
use PhpRedmin\Redis;
use PhpRedmin\Test\Phpunit\MiddlewareTestCase;
use PSR7Sessions\Storageless\Http\SessionMiddleware;
use PSR7Sessions\Storageless\Session\SessionInterface;
use Twig\Environment;

/**
 * @group middleware
 */
class TemplateTest extends MiddlewareTestCase
{
    protected $redis;
    protected $session;
    protected $twig;
    protected $user;

    public function setUp()
    {
        parent::setUp();

        $this->redis = $this->createMock(Redis::class);
        $this->session = $this->createMock(SessionInterface::class);
        $this->user = $this->createMock(User::class);
        $this->twig = $this->createMock(Environment::class);

        $this->request
            ->expects($this->once())
            ->method('getAttribute')
            ->with(SessionMiddleware::SESSION_ATTRIBUTE)
            ->willReturn($this->session);
    }

    public function testInvoke()
    {
        $this->session
            ->expects($this->once())
            ->method('has')
            ->with('email')
            ->willReturn(TRUE);

        $this->session
            ->expects($this->once())
            ->method('get')
            ->with('email')
            ->willReturn('alpha@bravo.com');

        $this->user
            ->expects($this->once())
            ->method('get')
            ->with('alpha@bravo.com')
            ->willReturn(['user-details']);

        $this->redis
            ->expects($this->once())
            ->method('getServerIndex')
            ->willReturn(1);

        $this->redis
            ->expects($this->once())
            ->method('getDbIndex')
            ->willReturn(2);

        $this->twig
            ->expects($this->exactly(3))
            ->method('addGlobal')
            ->withConsecutive(
                ['user', ['user-details']],
                ['serverIndex', '1'],
                ['dbIndex', '2']
            );

        $middleware = new Template(
            $this->redis,
            $this->user,
            $this->twig
        );

        $middleware($this->request, $this->response, $this->next);
    }

    public function testInvokeNotLoggedIn()
    {
        $this->session
            ->expects($this->once())
            ->method('has')
            ->with('email')
            ->willReturn(FALSE);

        $this->session
            ->expects($this->never())
            ->method('get');

        $this->user
            ->expects($this->never())
            ->method('get');

        $this->redis
            ->expects($this->once())
            ->method('getServerIndex')
            ->willReturn(1);

        $this->redis
            ->expects($this->once())
            ->method('getDbIndex')
            ->willReturn(2);

        $this->twig
            ->expects($this->exactly(2))
            ->method('addGlobal')
            ->withConsecutive(
                ['serverIndex', '1'],
                ['dbIndex', '2']
            );

        $middleware = new Template(
            $this->redis,
            $this->user,
            $this->twig
        );

        $middleware($this->request, $this->response, $this->next);
    }
}
