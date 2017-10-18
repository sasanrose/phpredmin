<?php

/**
 * This file is part of PHPRedmin project.
 *
 * (c) Sasan Rose <sasan.rose@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PhpRedmin\Test\Validator;

use PhpRedmin\Validator\FormValidator;
use PhpRedmin\Validator\FormValidatorInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @group validator
 */
class ValidatorTest extends TestCase
{
    public function testValidation()
    {
        $request = $this->createMock(ServerRequestInterface::class);

        $form = [
            'valid_email' => 'alpha(@bravo.com',
            'invalid_ip' => '4.4.4',
            'invalid_url' => 'wwww.phpredmin.com',
            'callback' => 'alpha',
            'optional_int' => ' ',
            'no_validation' => 'alpha-bravo',
            'empty_filters' => 'alpha-bravo',
        ];

        $request
            ->expects($this->once())
            ->method('getParsedBody')
            ->willReturn($form);

        $validator = $this->getValidator();

        $result = $validator->validate($request);

        $this->assertFalse($result);

        $errors = $validator->getErrors();

        $this->assertEquals(3, count($errors));
        $this->assertTrue(!isset($errors['valid_email']));

        $fields = $validator->getValues();

        $this->assertEquals(6, count($fields));
        $this->assertEquals('alpha@bravo.com', $fields['valid_email']);
    }

    protected function getValidator()
    {
        $validator = new FormValidator();

        $validator->addField(
            'valid_email',
            'Email address',
            FormValidatorInterface::REQUIRED,
            [FILTER_SANITIZE_EMAIL, FILTER_VALIDATE_EMAIL]
        )->addField(
            'invalid_ip',
            'Ip address',
            FormValidatorInterface::REQUIRED,
            [FILTER_VALIDATE_IP]
        )->addField(
            'invalid_url',
            'URL',
            FormValidatorInterface::REQUIRED,
            [FILTER_VALIDATE_URL],
            FILTER_FLAG_SCHEME_REQUIRED
        )->addField(
            'optional_int',
            'Integer',
            FormValidatorInterface::OPTIONAL,
            [FILTER_VALIDATE_INT]
        )->addField(
            'callback',
            'Callback',
            FormValidatorInterface::REQUIRED,
            [FILTER_CALLBACK],
            ['options' => function ($value) {
                return $value;
            }]
        )->addField(
            'no_validation',
            'No validation'
        )->addField(
            'required',
            'Required'
        )->addField(
            'empty_filters',
            'Empty filters',
            FormValidatorInterface::REQUIRED,
            []
        );

        return $validator;
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Duplicated field: alpha
     */
    public function testDuplicatedFields()
    {
        $validator = new FormValidator();
        $validator->addField('alpha', 'Alpha');
        $validator->addField('alpha', 'Alpha');
    }
}
