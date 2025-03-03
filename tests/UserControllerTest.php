<?php

require_once __DIR__ . '/MockPhpStream.php';
use PHPUnit\Framework\TestCase;
use App\Controllers\UserController;

class UserControllerTest extends TestCase
{
  protected function setUp(): void
  {
    $_SESSION['users'] = [];
    $_SESSION['nextId'] = 1;
  }

  // Test successfully creating a user
  public function testCreateUserSuccessfully()
  {
    echo "DEBUG: testCreateUserSuccessfully started...\n";

    $userData = json_encode([
      "firstName" => "John",
      "lastName" => "Doe",
      "email" => "john.doe@example.com",
      "dateOfBirth" => "1990-05-10"
    ]);

    if (in_array("php", stream_get_wrappers())) {
      stream_wrapper_unregister("php");
    }

    stream_wrapper_register("php", "MockPhpStream");
    MockPhpStream::setContent($userData);

    pcntl_alarm(5);

    ob_start();
    UserController::createUser();
    $output = ob_get_clean();

    pcntl_alarm(0);

    stream_wrapper_restore("php");

    $response = json_decode($output, true);

    echo "DEBUG: UserController::createUser() executed. Response: " . json_encode($response) . "\n";

    $this->assertNotNull($response, "Response should not be null");
    $this->assertArrayHasKey("id", $response, "Response should contain an 'id'");
    $this->assertEquals(1, $response["id"], "User ID should be 1");
  }

  // Test creating a user with an invalid email
  public function testCreateUserWithInvalidEmail()
  {
    echo "DEBUG: testCreateUserWithInvalidEmail started...\n";

    $userData = json_encode([
      "firstName" => "Jane",
      "lastName" => "Doe",
      "email" => "invalid-email",
      "dateOfBirth" => "1990-05-10"
    ]);

    if (in_array("php", stream_get_wrappers())) {
      stream_wrapper_unregister("php");
    }

    stream_wrapper_register("php", "MockPhpStream");
    MockPhpStream::setContent($userData);

    pcntl_alarm(5);

    ob_start();
    UserController::createUser();
    $output = ob_get_clean();

    pcntl_alarm(0);

    stream_wrapper_restore("php");

    $response = json_decode($output, true);

    echo "DEBUG: UserController::createUser() executed. Response: " . json_encode($response) . "\n";

    $this->assertNotNull($response, "Response should not be null");
    $this->assertArrayHasKey("error", $response, "Response should contain an 'error'");
    $this->assertEquals("Invalid email", $response["error"]);
  }

  // Test creating a user under 18 years old
  public function testCreateUserUnder18()
  {
    echo "DEBUG: testCreateUserUnder18 started...\n";

    $userData = json_encode([
      "firstName" => "Jake",
      "lastName" => "Smith",
      "email" => "jake.smith@example.com",
      "dateOfBirth" => "2010-05-10"
    ]);

    if (in_array("php", stream_get_wrappers())) {
      stream_wrapper_unregister("php");
    }

    stream_wrapper_register("php", "MockPhpStream");
    MockPhpStream::setContent($userData);

    pcntl_alarm(5);

    ob_start();
    UserController::createUser();
    $output = ob_get_clean();

    pcntl_alarm(0);

    stream_wrapper_restore("php");

    $response = json_decode($output, true);

    echo "DEBUG: UserController::createUser() executed. Response: " . json_encode($response) . "\n";

    $this->assertNotNull($response, "Response should not be null");
    $this->assertArrayHasKey("error", $response, "Response should contain an 'error'");
    $this->assertEquals("User must be 18+", $response["error"]);
  }

  // Test fetching all users when no users exist
  public function testGetAllUsersEmpty()
  {
    echo "DEBUG: testGetAllUsersEmpty started...\n";

    ob_start();
    UserController::getAllUsers();
    $output = ob_get_clean();

    $response = json_decode($output, true);

    echo "DEBUG: UserController::getAllUsers() executed. Response: " . json_encode($response) . "\n";

    $this->assertIsArray($response, "Response should be an array");
    $this->assertEmpty($response, "Response should be an empty array");
  }

  // Test fetching a user that does not exist
  public function testGetUserNotFound()
  {
    echo "DEBUG: testGetUserNotFound started...\n";

    ob_start();
    UserController::getUser(99);
    $output = ob_get_clean();

    $response = json_decode($output, true);

    echo "DEBUG: UserController::getUser() executed. Response: " . json_encode($response) . "\n";

    $this->assertNotNull($response, "Response should not be null");
    $this->assertArrayHasKey("error", $response, "Response should contain an 'error'");
    $this->assertEquals("User not found", $response["error"]);
  }

  // Test deleting a user that does not exist
  public function testDeleteUserNotFound()
  {
    echo "DEBUG: testDeleteUserNotFound started...\n";

    ob_start();
    UserController::deleteUser(99);
    $output = ob_get_clean();

    echo "DEBUG: Delete User Not Found Response: " . json_encode($output) . "\n";

    $this->assertEmpty($output, "Response should be empty");
  }


  // Test successfully deleting a user
  public function testDeleteUserSuccessfully()
  {
    echo "DEBUG: testDeleteUserSuccessfully started...\n";

    // Create user first
    $_SESSION['users'][1] = [
      "id" => 1,
      "firstName" => "John",
      "lastName" => "Doe",
      "email" => "john.doe@example.com",
      "dateOfBirth" => "1990-05-10",
      "age" => 34
    ];

    ob_start();
    UserController::deleteUser(1);
    $output = ob_get_clean();

    $this->assertEmpty($output, "Response should be empty");
    $this->assertArrayNotHasKey(1, $_SESSION['users'], "User should be removed from session");
  }

  // Test updating a user successfully
  public function testUpdateUserSuccessfully()
  {
    echo "DEBUG: testUpdateUserSuccessfully started...\n";

    $_SESSION['users'][1] = [
      "id" => 1,
      "firstName" => "John",
      "lastName" => "Doe",
      "email" => "john.doe@example.com",
      "dateOfBirth" => "1990-05-10",
      "age" => 34
    ];

    $userData = json_encode([
      "firstName" => "Johnny",
      "lastName" => "Doe",
      "email" => "john.doe@example.com",
      "dateOfBirth" => "1990-05-10"
    ]);

    if (in_array("php", stream_get_wrappers())) {
      stream_wrapper_unregister("php");
    }

    stream_wrapper_register("php", "MockPhpStream");
    MockPhpStream::setContent($userData);

    pcntl_alarm(5);

    ob_start();
    UserController::updateUser(1);
    $output = ob_get_clean();

    pcntl_alarm(0);

    stream_wrapper_restore("php");

    $response = json_decode($output, true);

    echo "DEBUG: UserController::updateUser() executed. Response: " . json_encode($response) . "\n";

    $this->assertEquals("Johnny", $response["firstName"], "First name should be updated");
  }
}
