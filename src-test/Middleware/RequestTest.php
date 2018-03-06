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

use PhpRedmin\Middleware\Request as RequestMiddleware;
use PhpRedmin\Test\Phpunit\MiddlewareTestCase;

/**
 * @group middleware
 */
class RequestTest extends MiddlewareTestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testNoKeysAndActions()
    {
        $this->request
            ->expects($this->exactly(2))
            ->method('withAttribute')
            ->withConsecutive(['action', ''], ['keys', []]);

        $this->request
            ->expects($this->exactly(2))
            ->method('getParsedBody')
            ->will($this->onConsecutiveCalls('', []));

        $this->invoke();
    }

    public function testOneKeyAndOneAction()
    {
        $this->request
            ->expects($this->exactly(2))
            ->method('withAttribute')
            ->withConsecutive(['action', 'action'], ['keys', ['key']]);

        $this->request
            ->expects($this->exactly(2))
            ->method('getParsedBody')
            ->will($this->onConsecutiveCalls(
                ['action' => ['action']],
                ['keys' => 'key']
            ));

        $this->invoke();
    }

    public function testKeyAndAction()
    {
        $this->request
            ->expects($this->exactly(2))
            ->method('withAttribute')
            ->withConsecutive(['action', 'action'], ['keys', ['key1', 'key2']]);

        $this->request
            ->expects($this->exactly(2))
            ->method('getParsedBody')
            ->will($this->onConsecutiveCalls(
                ['action' => 'action'],
                ['keys' => ['key1', 'key2']]
            ));

        $this->invoke();
    }

    protected function invoke()
    {
        $middleware = new RequestMiddleware();
        $middleware($this->request, $this->response, $this->next);
    }
}
