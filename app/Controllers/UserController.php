<?php

namespace App\Controllers;

use App\Services\UserService;

session_start();

class UserController
{
  private $userService;

  public function __construct()
  {
    $this->userService = new UserService();
  }

  public function getAllUsers()
  {
    echo json_encode($this->userService->getAllUsers());
  }

  public function getUser($id)
  {
    $user = $this->userService->getUser($id);
    if ($user) {
      echo json_encode($user);
    } else {
      http_response_code(404);
      echo json_encode(["error" => "User not found"]);
    }
  }

  public function createUser()
  {
    error_log("DEBUG: Entered createUser()");
    $data = json_decode(file_get_contents("php://input"), true);
    error_log("DEBUG: Data received: " . json_encode($data));

    $newUser = $this->userService->createUser($data);

    if (isset($newUser['error'])) {
      http_response_code(400);
    } else {
      http_response_code(201);
    }

    echo json_encode($newUser);
    error_log("DEBUG: Response: " . json_encode($newUser));
  }

  public function updateUser($id)
  {
    error_log("DEBUG: Entered updateUser() with ID " . $id);
    $data = json_decode(file_get_contents("php://input"), true);

    $updatedUser = $this->userService->updateUser($id, $data);

    if (isset($updatedUser['error'])) {
      http_response_code(400);
    } else {
      http_response_code(200);
    }

    echo json_encode($updatedUser);
  }

  public function deleteUser($id)
  {
    error_log("DEBUG: Entered deleteUser() with ID " . $id);

    $result = $this->userService->deleteUser($id);

    if (isset($result['error'])) {
      http_response_code(404);
    } else {
      http_response_code(204);
    }
  }
}
