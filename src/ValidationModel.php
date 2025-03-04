<?php

namespace Dusan\PhpMvc\Validation;


abstract class ValidationModel extends AbstractValidationModel
{
    protected $token = null;

    public function getToken() {
        return $this->token;
    }

    public function setToken($token) {
        $this->token = $token;
    }

}
