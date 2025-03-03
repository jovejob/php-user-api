<?php

require_once __DIR__ . '/../core/Router.php';
require_once __DIR__ . '/../controllers/UserController.php';

use App\Controllers\UserController;

$router = new Router();

$router->add('GET', '/api/users', [UserController::class, 'getAllUsers']);
$router->add('GET', '/api/users/(\d+)', [UserController::class, 'getUser']);
$router->add('POST', '/api/users', [UserController::class, 'createUser']);
$router->add('PUT', '/api/users/(\d+)', [UserController::class, 'updateUser']);
$router->add('DELETE', '/api/users/(\d+)', [UserController::class, 'deleteUser']);

$router->dispatch();
