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

use PhpRedmin\Test\Phpunit\Traits\Response as ResponseTrait;
use PhpRedmin\Validator\FormValidatorInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Redis;

class ControllerTestCase extends TestCase
{
    use ResponseTrait;

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
        $this->validator = $this->createMock(FormValidatorInterface::class);

        $this->init();
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
