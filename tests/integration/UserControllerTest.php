<?php

require_once __DIR__ . '/../MockPhpStream.php';
use PHPUnit\Framework\TestCase;
use App\Controllers\UserController;

class UserControllerTest extends TestCase
{
  protected function setUp(): void
  {
    $_SESSION['users'] = [];
    $_SESSION['nextId'] = 1;
  }

  public function testCreateUserSuccessfully()
  {
    $userData = json_encode([
      "firstName" => "John",
      "lastName" => "Doe",
      "email" => "john.doe@example.com",
      "dateOfBirth" => "1990-05-10"
    ]);

    stream_wrapper_unregister("php");
    stream_wrapper_register("php", "MockPhpStream");
    MockPhpStream::setContent($userData);

    ob_start();
    $controller = new UserController();
    $controller->createUser();
    $output = ob_get_clean();

    stream_wrapper_restore("php");

    $response = json_decode($output, true);
    $this->assertArrayHasKey("id", $response);
    $this->assertEquals(1, $response["id"]);
  }

  public function testGetAllUsersEmpty()
  {
    ob_start();
    $controller = new UserController();
    $controller->getAllUsers();
    $output = ob_get_clean();

    $response = json_decode($output, true);
    $this->assertEmpty($response);
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

    ob_start();
    $controller = new UserController();
    $controller->deleteUser(1);
    $output = ob_get_clean();

    $this->assertEmpty($output);
    $this->assertArrayNotHasKey(1, $_SESSION['users']);
  }
}
