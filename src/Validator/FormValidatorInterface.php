<?php

/**
 * This file is part of PHPRedmin project.
 *
 * (c) Sasan Rose <sasan.rose@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PhpRedmin\Validator;

use Psr\Http\Message\ServerRequestInterface;

/**
 * An interface to implement form validators in order to validate form fields
 * and/or sanitize the fields.
 */
interface FormValidatorInterface
{
    /**
     * Required field.
     *
     * @const REQUIRED
     */
    const REQUIRED = 1;

    /**
     * Optional field.
     *
     * @const OPTIONAL
     */
    const OPTIONAL = 2;

    /**
     * Adds a field to the form with validation rules.
     *
     * @param string    $fieldName  name of the field in the html form
     * @param string    $fieldLabel label of the field used for error messages
     * @param int       $required   a custom filter flag
     * @param array     $filters    the validation and santization flags
     * @param array|int $options    an integer flag or an array of options
     *
     * @return FormValidatorInterface
     */
    public function addField(
        string $fieldName,
        string $fieldLabel,
        int $required = self::REQUIRED,
        array $filters = [],
        $options = NULL
    ) : FormValidatorInterface;

    /**
     * Validates the form based on fields and server request.
     *
     * @param ServerRequestInterface $request the server request form
     *                                        containing the parsed body
     *
     * @return bool
     */
    public function validate(ServerRequestInterface $request) : bool;

    /**
     * Returns a list of errors if any.
     *
     * @return array
     */
    public function getErrors() : array;

    /**
     * Returns a list of values of form fields.
     *
     * @return array
     */
    public function getValues() : array;
}
