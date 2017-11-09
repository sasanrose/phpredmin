<?php

/**
 * This file is part of PHPRedmin project.
 *
 * (c) Sasan Rose <sasan.rose@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PhpRedmin\Test\Phpunit;

use PhpRedmin\Integration\Zend\Diactoros\Response;
use PhpRedmin\Validator\FormValidatorInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Log\LoggerInterface;
use Redis;
use Twig\Environment;

class ControllerTestCase extends TestCase
{
    /**
     * Logger.
     *
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Redis mock.
     *
     * @var Redis
     */
    protected $redis;

    /**
     * Request mock.
     *
     * @var ServerRequestInterface
     */
    protected $request;

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
     * Form validator.
     *
     * @var FormValidatorInterface
     */
    protected $validator;

    /**
     * Creates all the required mocks to test a Controller method.
     */
    public function setUp()
    {
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->redis = $this->createMock(Redis::class);
        $this->request = $this->createMock(ServerRequestInterface::class);
        $this->response = $this->createMock(Response::class);
        $this->responseBody = $this->createMock(StreamInterface::class);
        $this->twig = $this->createMock(Environment::class);
        $this->validator = $this->createMock(FormValidatorInterface::class);
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

    /**
     * Mocks form validation.
     *
     * @param int   $numOfFields
     * @param bool  $validationResult
     * @param array $values
     * @param array $errors
     */
    protected function mockValidation(int $numOfFields, bool $validationResult, array $values = [], array $errors = [])
    {
        $this->validator
            ->expects($this->exactly($numOfFields))
            ->method('addField');

        $this->validator
            ->expects($this->once())
            ->method('validate')
            ->with($this->request)
            ->willReturn($validationResult);

        if (FALSE === $validationResult) {
            $getErrors = $this->validator
                ->expects($this->once())
                ->method('getErrors');

            if (!empty($errors)) {
                $getErrors->willReturn($errors);
            }
        }

        $getValues = $this->validator
            ->expects($this->once())
            ->method('getValues')
            ->willReturn($values);

        if (!empty($values)) {
            $getValues->willReturn($values);
        }
    }
}
