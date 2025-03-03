<?php

namespace App\Validators;

class UserValidator
{
  public static function validate($data)
  {
    if (empty($data['firstName']) || strlen($data['firstName']) > 128) {
      http_response_code(400);
      echo json_encode(["error" => "First name is required and must be under 128 characters"]);
      return false;
    }

    if (!empty($data['lastName']) && strlen($data['lastName']) > 128) {
      http_response_code(400);
      echo json_encode(["error" => "Last name must be under 128 characters"]);
      return false;
    }

    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
      http_response_code(400);
      echo json_encode(["error" => "Invalid email"]);
      return false;
    }

    if (!isset($data['dateOfBirth']) || !strtotime($data['dateOfBirth']) || self::calculateAge($data['dateOfBirth']) < 18) {
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
}
