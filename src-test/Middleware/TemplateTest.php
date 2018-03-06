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
use PhpRedmin\Redis;
use PhpRedmin\Test\Phpunit\MiddlewareTestCase;
use Pimple\Container;
use PSR7Sessions\Storageless\Http\SessionMiddleware;
use PSR7Sessions\Storageless\Session\SessionInterface;
use Twig\Environment;

/**
 * @group middleware
 */
class TemplateTest extends MiddlewareTestCase
{
    protected $container;
    protected $redis;
    protected $session;
    protected $twig;

    public function setUp()
    {
        parent::setUp();

        $this->container = new Container();
        $this->redis = $this->createMock(Redis::class);
        $this->session = $this->createMock(SessionInterface::class);
        $this->twig = $this->createMock(Environment::class);

        $this->container['REDIS_SERVERS'] = [
            ['ADDR' => 'redis0', 'PORT' => 63790, 'PASS' => 'alpha'],
            ['ADDR' => 'redis1', 'PORT' => 63791],
        ];

        $this->request
            ->expects($this->exactly(3))
            ->method('getAttribute')
            ->withConsecutive(
                [SessionMiddleware::SESSION_ATTRIBUTE],
                ['action'],
                ['keys', []]
            )
            ->will($this->onConsecutiveCalls($this->session, 'action', ['key']));

        $this->redis
            ->expects($this->once())
            ->method('getServerIndex')
            ->willReturn(1);

        $this->redis
            ->expects($this->once())
            ->method('getDbIndex')
            ->willReturn(2);

        $this->redis
            ->expects($this->once())
            ->method('info')
            ->with('keyspace')
            ->willReturn(['db0' => 'keys=13']);
    }

    public function testInvoke()
    {
        $this->session
            ->expects($this->once())
            ->method('has')
            ->with('email')
            ->willReturn(TRUE);

        $this->session
            ->expects($this->exactly(2))
            ->method('get')
            ->withConsecutive(
                ['email'],
                ['user']
            )
            ->will(
                $this->onConsecutiveCalls(
                    'alpha@bravo.com',
                    ['user-details']
                )
            );

        $this->twig
            ->expects($this->exactly(8))
            ->method('addGlobal')
            ->withConsecutive(
                ['email', 'alpha@bravo.com'],
                ['user', ['user-details']],
                ['selectedServerIndex', '1'],
                ['selectedDbIndex', '2'],
                ['servers', $this->container['REDIS_SERVERS']],
                ['dbs', [['keys' => 13]]],
                ['requestedAction', 'action'],
                ['requestedKeys', ['key']]
            );

        $middleware = new Template(
            $this->container,
            $this->redis,
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

        $this->twig
            ->expects($this->exactly(6))
            ->method('addGlobal')
            ->withConsecutive(
                ['selectedServerIndex', '1'],
                ['selectedDbIndex', '2'],
                ['servers', $this->container['REDIS_SERVERS']],
                ['dbs', [['keys' => 13]]],
                ['requestedAction', 'action'],
                ['requestedKeys', ['key']]
            );

        $middleware = new Template(
            $this->container,
            $this->redis,
            $this->twig
        );

        $middleware($this->request, $this->response, $this->next);
    }
}
