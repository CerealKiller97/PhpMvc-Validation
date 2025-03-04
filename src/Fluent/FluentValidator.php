<?php
declare(strict_types=1);

namespace Dusan\PhpMvc\Validation\Fluent;


use Dusan\PhpMvc\Validation\AbstractValidationModel;
use \Dusan\PhpMvc\Validation\Fluent\IValidator;
use Closure;
use TypeError;
/**
 * Class FluentValidator
 * @example "../../docs/Fluent/UserFluentValidator.php"
 * @package Dusan\PhpMvc\Validation\Fluent
 */
abstract class FluentValidator
{
    /**
     * If error occurred it will be added into errors and validation
     * will continue on that same value until end of validators is reached
     *
     * This is default behaviour
     */
    const CONTINUE_ON_ERROR = 1;

    /**
     * If error is occurred it wil be added into errors, but validation on that same
     * value will stop, and next value will be fetched, process repeats
     */
    const BREAK_ON_ERROR = 2;

    /**
     * As soon as any error occurred whole loop is stopped and error is returned
     */
    const BREAK_ON_ERROR_FULLY = 3;

    /**
     * @var \Dusan\PhpMvc\Validation\Fluent\Validation
     */
    protected $validations = [];

    protected $model;

    public function __construct(AbstractValidationModel $model)
    {
        $this->model = $model;
    }


    /**
     * Prepares validation for the given property in model
     *
     * @param string|Closure|callback $arg
     * @param string|null
     *
     * @throws TypeError
     * @return Validation
     */
    public final function forMember($arg, ?string $name = NULL): Validation
    {
        $validator = new Validation();
        
        $value = NULL;

        if(is_string($arg)) {
            $value = $arg;
        } else if(is_callable($arg) || $arg instanceof Closure) {
            $value = $arg($this->model, $name);
        } else { 
            throw new TypeError('First argument must be either callble or string');
        }

        if ($name !== NULL) {
            $this->validations[$name] = [
                'validation' => $validator,
                'value' => $value,
            ];
        } else {
            $this->validations[] = [
                'validation' => $validator,
                'value' => $value,
            ];
        }

        return $validator;
    }

    /**
     * Validates the model and returns the errors array if any error has been detected
     * if no errors were found, NULL is returned
     * Why NULL?
     * It's more convenient than to check length of the array
     *
     * @param int $flag
     *
     * @return array|null
     */
    public final function validate(int $flag = self::CONTINUE_ON_ERROR): ?array
    {
        $errors = [];
        foreach ($this->validations as $name => $v) {
            $value = $v['value'];
            /**
             * @var Validation $validation
             */
            $validation = $v['validation'];
            foreach ($validation->getValidators() as $validator) {
                /** @var IValidator $val */
                $val = $validator['validator'];
                $message = $validator['message'];
                if ($val->validate($value) === false) {
                    $errors[$name][] = $message;
                    // This could be &&, but for performance reasons it's split into two ifs
                    // Its better to compare two integers than to calculate the length of the array
                    // on each loop
                    if ($flag === self::BREAK_ON_ERROR_FULLY) {
                        if (count($errors) > 0) {
                            return $errors;
                        }
                    }
                    if ($flag === self::BREAK_ON_ERROR) break;
                }
            }
        }
        return count($errors) > 0 ? $errors : NULL;
    }
}
