<?php

/**
 * This file is part of PHPRedmin project.
 *
 * (c) Sasan Rose <sasan.rose@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PhpRedmin\Test\Validator\Traits;

use PhpRedmin\Validator\FormValidatorInterface;
use PhpRedmin\Validator\Traits\Error;
use PHPUnit\Framework\TestCase;

/**
 * @group validator
 */
class ErrorTest extends TestCase
{
    use Error;

    public function testError()
    {
        $tests = [
            'Alpha is required.' => [FormValidatorInterface::REQUIRED, 'Alpha'],
            'Bravo is an invalid email address.' => [FILTER_VALIDATE_EMAIL, 'Bravo'],
            'Bravo is invalid.' => [0, 'Bravo'],
            'Bravo has errors. Reason: nothing' => [FILTER_CALLBACK, 'Bravo', [
                'reason' => 'nothing',
                'errorMsg' => function ($label, $options = []) {
                    return "{$label} has errors. Reason: {$options['reason']}";
                },
            ]],
        ];

        foreach ($tests as $expected => $args) {
            $got = call_user_func_array([$this, 'getMessage'], $args);
            $this->assertEquals($expected, $got);
        }
    }
}
