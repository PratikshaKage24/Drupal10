<?php

namespace Drupal\registration_form\Service;

use Drupal\Core\Database\Connection;
use Drupal\Core\Database\Database;

class RegistrationService {
  
  public $database;

  public function __construct(Connection $database) {
    $this->database = $database;
  }

  public function save(array $data) {

    $this->database->insert('registrationdata')
      ->fields([
        'first_name' => $data['first_name'],
        'last_name' => $data['last_name'],
        'mobile' => $data['mobile'],
        'email' => $data['email'],
        'bio' => $data['bio'],
      ])
      ->execute();
  }
}

