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

use PhpRedmin\Validator\FormValidatorInterface;

trait Error
{
    /**
     * Returns an appropriate error message for a form validation error.
     *
     * @param int    $filter
     * @param string $fieldLabel
     * @param mixed  $options
     *
     * @return string
     */
    protected function getMessage(int $filter, string $fieldLabel, $options = NULL): string
    {
        // Default error message
        $msg = _('%s is invalid.');

        switch ($filter) {
        case FormValidatorInterface::REQUIRED:
            $msg = _('%s is required.');
            break;
        case FILTER_VALIDATE_EMAIL:
            $msg = _('%s is an invalid email address.');
            break;
        }

        if (isset($options['errorMsg']) && is_callable($options['errorMsg'])) {
            $msg = $options['errorMsg']($fieldLabel, $options);
        }

        return sprintf($msg, $fieldLabel);
    }
}
