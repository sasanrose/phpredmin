<?php

/**
 * This file is part of PHPRedmin project.
 *
 * (c) Sasan Rose <sasan.rose@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PhpRedmin\Test\Integration\League\Route\Strategy;

use League\Route\Http\Exception\MethodNotAllowedException;
use League\Route\Http\Exception\NotFoundException;
use PhpRedmin\Integration\League\Route\Strategy\Application;
use PhpRedmin\Test\Phpunit\Traits\Response as ResponseTrait;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;

/**
 * @group integration
 */
class ApplicationTest extends TestCase
{
    use ResponseTrait;

    protected $logger;
    protected $request;

    public function setUp()
    {
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->request = $this->createMock(ServerRequestInterface::class);

        $this->init();
    }

    public function testNotFound()
    {
        $this->runStrategy(
            'getNotFoundDecorator',
            new NotFoundException(),
            'controller/misc/not-found.twig'
        );
    }

    public function testMethodNotAllowed()
    {
        $this->runStrategy(
            'getMethodNotAllowedDecorator',
            new MethodNotAllowedException(),
            'controller/misc/not-found.twig'
        );
    }

    public function testException()
    {
        $this->logger
            ->expects($this->once())
            ->method('error')
            ->with('Test message');

        $this->runStrategy(
            'getExceptionDecorator',
            new \Exception('Test message'),
            'controller/misc/error.twig'
        );
    }

    protected function runStrategy(string $method, \Exception $exception, string $template)
    {
        $this->mockResponse($template);

        $app = new Application(
            $this->twig,
            $this->logger
        );

        $callable = call_user_func([$app, $method], $exception);

        $this->assertInternalType('callable', $callable);

        $response = call_user_func($callable, $this->request, $this->response);

        $this->assertTrue($response instanceof ResponseInterface);
    }
}
