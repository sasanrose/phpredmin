<?php

/**
 * This file is part of PHPRedmin project.
 *
 * (c) Sasan Rose <sasan.rose@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PhpRedmin\Validator\Traits;

trait Password
{
    /**
     * Password regex.
     *
     * Password expresion that requires one lower case letter, one upper case
     * letter, one digit, at least 6 chars, and no spaces.
     *
     * @var string
     */
    protected $passwordRegex = '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).{6,}$/';

    /**
     * Validates a password.
     *
     * @param string $password
     *
     * @return mixed
     */
    public function validatePassword(string $password)
    {
        if (preg_match($this->passwordRegex, $password)) {
            return $password;
        }

        return FALSE;
    }

    /**
     * Returns an error message for password validation.
     *
     * @param string $label
     * @param array  $options
     *
     * @return string
     */
    public function getPasswordValidationErrorMsg(string $label, array $options = []) : string
    {
        return sprintf(_('%s does not match the password policy'), $label);
    }
}
