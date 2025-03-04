<?php
declare(strict_types=1);

namespace Dusan\PhpMvc\Validation\Fluent\Validators;


use Dusan\PhpMvc\Validation\Fluent\IValidator;


/**
 *
 *
 * @package Dusan\PhpMvc\Validation\Fluent\Validators
 */
abstract class AbstractFluentValidator implements IValidator
{
    /**
     * Checks if the value is empty, if it is,
     * validation in validate method will be ignored
     * @param string|array|float|int|object $value
     * If you don't want your value to be optional use NotEmpty validator or
     * simply don't put optional call in validation method
     * @return bool|null
     */
    protected final function optional($value): ?bool
    {
        if (empty($value)) {
            return true;
        }
        return NULL;
    }
}
