<?php

namespace App\Models;

class User
{
  public $id;
  public $firstName;
  public $lastName;
  public $email;
  public $dateOfBirth;

  public function __construct($id, $firstName, $lastName, $email, $dateOfBirth)
  {
    $this->id = $id;
    $this->firstName = $firstName;
    $this->lastName = $lastName;
    $this->email = $email;
    $this->dateOfBirth = $dateOfBirth;
  }

  public function getAge()
  {
    $dob = new \DateTime($this->dateOfBirth);
    $today = new \DateTime();
    return $dob->diff($today)->y;
  }

  public function toArray()
  {
    return [
      "id" => $this->id,
      "firstName" => $this->firstName,
      "lastName" => $this->lastName,
      "email" => $this->email,
      "dateOfBirth" => $this->dateOfBirth,
      "age" => $this->getAge()
    ];
  }
}
