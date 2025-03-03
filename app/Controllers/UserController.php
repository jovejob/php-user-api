<?php

namespace App\Controllers;

use App\Models\User;

session_start();

class UserController
{
  public static function getAllUsers()
  {
    $users = $_SESSION['users'] ?? [];
    echo json_encode(array_values($users));
  }

  public static function getUser($id)
  {
    $users = $_SESSION['users'] ?? [];
    if (isset($users[$id])) {
      echo json_encode($users[$id]);
    } else {
      http_response_code(404);
      echo json_encode(["error" => "User not found"]);
    }
  }

  public static function createUser()
  {
    error_log("DEBUG: Entered createUser()");

    $data = json_decode(file_get_contents("php://input"), true);

    error_log("DEBUG: Data received: " . json_encode($data));

    if (!self::validateUser($data)) {
      error_log("DEBUG: Validation failed");
      return;
    }

    if (self::emailExists($data['email'])) {
      error_log("DEBUG: Email already exists");
      http_response_code(400);
      echo json_encode(["error" => "Email must be unique"]);
      return;
    }

    $id = $_SESSION['nextId'] ?? 1;
    $newUser = new User($id, $data['firstName'], $data['lastName'] ?? '', $data['email'], $data['dateOfBirth']);

    $_SESSION['users'][$id] = $newUser->toArray();
    $_SESSION['nextId'] = $id + 1;

    http_response_code(201);
    echo json_encode($newUser->toArray());

    error_log("DEBUG: Successfully created user with ID $id");
  }

  public static function updateUser($id)
  {
    error_log("DEBUG: Entered updateUser() with ID " . $id);

    $data = json_decode(file_get_contents("php://input"), true);
    $users = $_SESSION['users'] ?? [];

    error_log("DEBUG: Data received for update: " . json_encode($data));

    if (!isset($users[$id])) {
      error_log("DEBUG: User not found");
      http_response_code(404);
      echo json_encode(["error" => "User not found"]);
      return;
    }

    if (!self::validateUser($data)) {
      error_log("DEBUG: Validation failed for update");
      return;
    }

    $users[$id] = [
      "id" => $id,
      "firstName" => $data['firstName'],
      "lastName" => $data['lastName'] ?? '',
      "email" => $data['email'],
      "dateOfBirth" => $data['dateOfBirth'],
      "age" => self::calculateAge($data['dateOfBirth'])
    ];

    $_SESSION['users'] = $users;

    http_response_code(200);
    echo json_encode($users[$id]);

    error_log("DEBUG: Successfully updated user with ID $id");
  }

  public static function deleteUser($id)
  {
    error_log("DEBUG: Entered deleteUser() with ID " . $id);

    $users = $_SESSION['users'] ?? [];

    if (!isset($users[$id])) {
      error_log("DEBUG: User not found for deletion");
      http_response_code(404);
      return; // Ensure response is empty
    }

    unset($_SESSION['users'][$id]);
    http_response_code(204);

    error_log("DEBUG: Successfully deleted user with ID $id");
  }


  private static function validateUser($data)
  {
    if (empty($data['firstName']) || strlen($data['firstName']) > 128) {
      error_log("DEBUG: Validation failed - First name invalid");
      http_response_code(400);
      echo json_encode(["error" => "First name is required and must be under 128 characters"]);
      return false;
    }

    if (!empty($data['lastName']) && strlen($data['lastName']) > 128) {
      error_log("DEBUG: Validation failed - Last name invalid");
      http_response_code(400);
      echo json_encode(["error" => "Last name must be under 128 characters"]);
      return false;
    }

    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
      error_log("DEBUG: Validation failed - Invalid email");
      http_response_code(400);
      echo json_encode(["error" => "Invalid email"]);
      return false;
    }

    if (!isset($data['dateOfBirth']) || !strtotime($data['dateOfBirth']) || self::calculateAge($data['dateOfBirth']) < 18) {
      error_log("DEBUG: Validation failed - User under 18 or invalid date");
      http_response_code(400);
      echo json_encode(["error" => "User must be 18+"]);
      return false;
    }

    return true;
  }

  private static function calculateAge($dob)
  {
    return (new \DateTime($dob))->diff(new \DateTime())->y;
  }

  private static function emailExists($email)
  {
    $users = $_SESSION['users'] ?? [];
    foreach ($users as $user) {
      if ($user['email'] == $email) {
        error_log("DEBUG: Email already exists - " . $email);
        return true;
      }
    }
    return false;
  }
}
