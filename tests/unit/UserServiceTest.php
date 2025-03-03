<?php

use PHPUnit\Framework\TestCase;
use App\Services\UserService;

class UserServiceTest extends TestCase
{
  private $userService;

  protected function setUp(): void
  {
    $_SESSION['users'] = [];
    $_SESSION['nextId'] = 1;
    $this->userService = new UserService();
  }

  public function testCreateUserSuccessfully()
  {
    $data = [
      "firstName" => "John",
      "lastName" => "Doe",
      "email" => "john.doe@example.com",
      "dateOfBirth" => "1990-05-10"
    ];

    $result = $this->userService->createUser($data);

    $this->assertIsArray($result);
    $this->assertArrayHasKey("id", $result);
    $this->assertEquals(1, $result["id"]);
  }

  public function testCreateDuplicateEmailFails()
  {
    $data = [
      "firstName" => "John",
      "lastName" => "Doe",
      "email" => "john.doe@example.com",
      "dateOfBirth" => "1990-05-10"
    ];

    $this->userService->createUser($data);
    $result = $this->userService->createUser($data);

    $this->assertEquals(["error" => "Email must be unique"], $result);
  }

  public function testDeleteUserSuccessfully()
  {
    $_SESSION['users'][1] = [
      "id" => 1,
      "firstName" => "John",
      "lastName" => "Doe",
      "email" => "john.doe@example.com",
      "dateOfBirth" => "1990-05-10"
    ];

    $result = $this->userService->deleteUser(1);
    $this->assertNull($result);
    $this->assertArrayNotHasKey(1, $_SESSION['users']);
  }
}
