<?php

/**
 * This file is part of PHPRedmin project.
 *
 * (c) Sasan Rose <sasan.rose@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PhpRedmin\Test\Phpunit\Traits;

use PhpRedmin\Integration\Zend\Diactoros\Response as ZendResponse;
use Psr\Http\Message\StreamInterface;
use Twig\Environment;

trait Response
{
    /**
     * Response mock.
     *
     * @var Response
     */
    protected $response;

    /**
     * Response body mock.
     *
     * @var StreamInterface
     */
    protected $responseBody;

    /**
     * Twig mock.
     *
     * @var Twig\Environment
     */
    protected $twig;

    /**
     * Inits mocks.
     */
    protected function init()
    {
        $this->response = $this->createMock(ZendResponse::class);
        $this->responseBody = $this->createMock(StreamInterface::class);
        $this->twig = $this->createMock(Environment::class);
    }

    /**
     * Mocks the response of a Controller method.
     *
     * @param string $template
     * @param array  $variables
     */
    protected function mockResponse(string $template, array $variables = [])
    {
        $this->responseBody
            ->expects($this->once())
            ->method('write');

        $this->response
            ->expects($this->once())
            ->method('getBody')
            ->willReturn($this->responseBody);

        $render = $this->twig
            ->expects($this->once())
            ->method('render');

        if (!empty($variables)) {
            $render->with($template, $variables);

            return;
        }

        $render->with($template);
    }
}
