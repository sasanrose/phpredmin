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
 * {@inheritdoc}
 */
class FormValidator implements FormValidatorInterface
{
    use Traits\Error;

    /**
     * List of fields.
     *
     * @var array
     */
    protected $fields = [];

    /**
     * List of errors.
     *
     * @var array
     */
    protected $errors = [];

    /**
     * List of field values.
     *
     * @var array
     */
    protected $fieldValues = [];

    /**
     * {@inheritdoc}
     */
    public function addField(
        string $fieldName,
        string $fieldLabel,
        int $required = self::REQUIRED,
        array $filters = [],
        $options = NULL
    ): FormValidatorInterface {
        if (isset($this->fields[$fieldName])) {
            throw new \Exception("Duplicated field: {$fieldName}");
        }

        $this->fields[$fieldName] = [
            'label' => $fieldLabel,
            'filters' => $filters,
            'required' => $required,
            'options' => $options,
        ];

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function validate(ServerRequestInterface $request): bool
    {
        $body = $request->getParsedBody();

        $result = TRUE;

        foreach ($this->fields as $fieldName => $field) {
            // If field is required but not set
            if (
                FormValidatorInterface::REQUIRED === $field['required'] &&
                !$this->hasField($body, $fieldName)
            ) {
                $result = FALSE;
                $this->errors[$fieldName] = $this->getMessage(
                    FormValidatorInterface::REQUIRED,
                    $field['label'],
                    $field['options']
                );
                continue;
            }

            // If field is optional and not set
            if (
                FormValidatorInterface::OPTIONAL === $field['required'] &&
                !$this->hasField($body, $fieldName)
            ) {
                $result = $result && TRUE;
                continue;
            }

            $fieldValue = trim($body[$fieldName]);

            // Nothing to filter
            if (!isset($field['filters']) || empty($field['filters'])) {
                $this->fieldValues[$fieldName] = $fieldValue;
                continue;
            }

            foreach ($field['filters'] as $filter) {
                $fieldValue = filter_var($fieldValue, $filter, $field['options']);

                if (FALSE === $fieldValue) {
                    $result = FALSE;

                    $this->fieldValues[$fieldName] = trim($body[$fieldName]);
                    $this->errors[$fieldName] = $this->getMessage($filter, $field['label'], $field['options']);
                    continue 2;
                }
            }

            $this->fieldValues[$fieldName] = $fieldValue;
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * {@inheritdoc}
     */
    public function getValues(): array
    {
        return $this->fieldValues;
    }

    /**
     * Check if a field has value in the body of request.
     *
     * @param array  $body
     * @param string $fieldName
     *
     * @return bool
     */
    protected function hasField(array $body, string $fieldName): bool
    {
        return isset($body[$fieldName]) &&
            !empty($body[$fieldName]) &&
            (!(string) $body[$fieldName] || '' !== trim($body[$fieldName]));
    }
}
