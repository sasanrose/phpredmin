<?php

/**
 * This file is part of PHPRedmin project.
 *
 * (c) Sasan Rose <sasan.rose@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PhpRedmin\Test\Traits;

use PhpRedmin\Traits;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;

class RequestTrait extends TestCase
{
    use Traits\Request;

    protected $request;

    public function setUp()
    {
        $this->request = $this->createMock(ServerRequestInterface::class);
    }

    public function testQuery()
    {
        $this->request
            ->expects($this->once())
            ->method('getQueryParams')
            ->willReturn(['key' => 'value']);

        $this->request
            ->expects($this->never())
            ->method('getParsedBody');

        $this->assertEquals('value', $this->getValueFromRequest($this->request, 'key'));
    }

    public function testBody()
    {
        $this->request
            ->expects($this->once())
            ->method('getQueryParams')
            ->willReturn([]);

        $this->request
            ->expects($this->once())
            ->method('getParsedBody')
            ->willReturn(['key' => 'value']);

        $this->assertEquals('value', $this->getValueFromRequest($this->request, 'key'));
    }

    public function testDefault()
    {
        $this->request
            ->expects($this->once())
            ->method('getQueryParams')
            ->willReturn([]);

        $this->request
            ->expects($this->once())
            ->method('getParsedBody')
            ->willReturn([]);

        $this->assertEquals('default', $this->getValueFromRequest($this->request, 'key', 'default'));
    }
}
