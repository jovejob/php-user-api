<?php

namespace App\Services;

use App\Models\User;
use App\Validators\UserValidator;

class UserService
{
  public function getAllUsers()
  {
    return $_SESSION['users'] ?? [];
  }

  public function getUser($id)
  {
    return $_SESSION['users'][$id] ?? null;
  }

  public function createUser($data)
  {
    if (!UserValidator::validate($data)) {
      return ["error" => "Validation failed"];
    }

    if ($this->emailExists($data['email'])) {
      return ["error" => "Email must be unique"];
    }

    $id = $_SESSION['nextId'] ?? 1;
    $newUser = new User($id, $data['firstName'], $data['lastName'] ?? '', $data['email'], $data['dateOfBirth']);

    $_SESSION['users'][$id] = $newUser->toArray();
    $_SESSION['nextId'] = $id + 1;

    return $newUser->toArray();
  }

  public function updateUser($id, $data)
  {
    if (!isset($_SESSION['users'][$id])) {
      return ["error" => "User not found"];
    }

    if (!UserValidator::validate($data)) {
      return ["error" => "Validation failed"];
    }

    $_SESSION['users'][$id] = [
      "id" => $id,
      "firstName" => $data['firstName'],
      "lastName" => $data['lastName'] ?? '',
      "email" => $data['email'],
      "dateOfBirth" => $data['dateOfBirth'],
      "age" => $this->calculateAge($data['dateOfBirth'])
    ];

    return $_SESSION['users'][$id];
  }

  public function deleteUser($id)
  {
    if (!isset($_SESSION['users'][$id])) {
      return ["error" => "User not found"];
    }

    unset($_SESSION['users'][$id]);
    return null;
  }

  private function emailExists($email)
  {
    foreach ($_SESSION['users'] ?? [] as $user) {
      if ($user['email'] == $email) {
        return true;
      }
    }
    return false;
  }

  private function calculateAge($dob)
  {
    return (new \DateTime($dob))->diff(new \DateTime())->y;
  }
}
