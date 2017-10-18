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

use PhpRedmin\Validator\Traits\Password;
use PHPUnit\Framework\TestCase;

/**
 * @group validator
 */
class PasswordTest extends TestCase
{
    use Password;

    public function testInvalid()
    {
        $invalidPasswords = [
            'This is a test',
            'This',
            'thisisatest',
            'Thisisatest',
        ];

        foreach ($invalidPasswords as $password) {
            $this->assertFalse($this->validatePassword($password));
        }
    }

    public function testValid()
    {
        $invalidPasswords = [
            'Thisisatest1',
            'This1234',
            'tHis,is,a,test1234',
            'Thisisatest#1234',
        ];

        foreach ($invalidPasswords as $password) {
            $this->assertEquals($password, $this->validatePassword($password));
        }
    }

    public function testMsg()
    {
        $msg = 'Password does not match the password policy';

        $this->assertEquals($msg, $this->getPasswordValidationErrorMsg('Password'));
    }
}
