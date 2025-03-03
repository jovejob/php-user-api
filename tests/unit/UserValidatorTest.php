<?php

use PHPUnit\Framework\TestCase;
use App\Validators\UserValidator;

class UserValidatorTest extends TestCase
{
  public function testValidateValidUser()
  {
    $data = [
      "firstName" => "John",
      "lastName" => "Doe",
      "email" => "john.doe@example.com",
      "dateOfBirth" => "1990-05-10"
    ];

    $this->assertTrue(UserValidator::validate($data));
  }

  public function testValidateInvalidEmail()
  {
    $data = [
      "firstName" => "John",
      "lastName" => "Doe",
      "email" => "invalid-email",
      "dateOfBirth" => "1990-05-10"
    ];

    $this->assertFalse(UserValidator::validate($data));
  }

  public function testValidateUserUnder18()
  {
    $data = [
      "firstName" => "John",
      "lastName" => "Doe",
      "email" => "john.doe@example.com",
      "dateOfBirth" => "2010-05-10"
    ];

    $this->assertFalse(UserValidator::validate($data));
  }
}
