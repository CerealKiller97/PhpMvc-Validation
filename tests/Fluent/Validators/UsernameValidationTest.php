<?php

namespace Dusan\PhpMvc\Tests\Validation\Fluent\Validators;

use Dusan\PhpMvc\Validation\Fluent\Validators\Username;
use PHPUnit\Framework\TestCase;

class UsernameValidatonTest extends TestCase
{
    public function test_validation_good_username() {
        $password = 'GoodUsername';

        $passwordValidator = new Username();

        $this->assertTrue($passwordValidator->validate($password));
    }

    public function test_bad_username() {
        $password = 'thisisbadpassword12#';

        $passwordValidator = new Username();

        $this->assertFalse($passwordValidator->validate($password));
    }
}
