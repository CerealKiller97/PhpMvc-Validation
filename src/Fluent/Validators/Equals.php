<?php


namespace Dusan\PhpMvc\Validation\Fluent\Validators;


class Equals extends AbstractFluentValidator
{
    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * @param string|array|integer|float|object $value
     *
     * @return bool
     */
    public function validate($value): bool
    {
        if (is_string($value) && is_string($this->value)) {
            return strcmp($value, $this->value) === 0;
        }
        return $this->value === $value;
    }
}
