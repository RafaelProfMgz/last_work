<?php

use App\Controller\EntryController;
use App\Controller\ExpensesController;
use App\Controller\UserController;
use App\Controller\AuthController;

$router->addRoute('GET', '/entradas', [EntryController::class, 'findAll']);
$router->addRoute('POST', '/entradas', [EntryController::class, 'create']);
$router->addRoute('GET', '/entradas/{id}', [EntryController::class, 'findOne']);
$router->addRoute('PUT', '/entradas/{id}', [EntryController::class, 'update']);
$router->addRoute('DELETE', '/entradas/{id}', [EntryController::class, 'delete']);
$router->addRoute('DELETE', '/entradas', [EntryController::class, 'deleteMany']);
$router->addRoute('PUT', '/entradas', [EntryController::class, 'updateMany']);
$router->addRoute('POST', '/entradas/many', [EntryController::class, 'createMany']);

$router->addRoute('GET', '/saidas', [ExpensesController::class, 'list']);
$router->addRoute('POST', '/saidas', [ExpensesController::class, 'create']);
$router->addRoute('GET', '/saidas/{id}', [ExpensesController::class, 'read']);
$router->addRoute('PUT', '/saidas/{id}', [ExpensesController::class, 'update']);
$router->addRoute('DELETE', '/saidas/{id}', [ExpensesController::class, 'delete']);

$router->addRoute('POST', '/users', [UserController::class, 'create']);
$router->addRoute('GET', '/users', [UserController::class, 'findAll']);
$router->addRoute('GET', '/users/{id}', [UserController::class, 'findOne']);
$router->addRoute('PUT', '/users/{id}', [UserController::class, 'update']);
$router->addRoute('DELETE', '/users/{id}', [UserController::class, 'delete']);
$router->addRoute('DELETE', '/users', [UserController::class, 'deleteMany']);
$router->addRoute('PUT', '/users', [UserController::class, 'updateMany']);
$router->addRoute('POST', '/users/many', [UserController::class, 'createMany']);

$router->addRoute('POST', '/register', [AuthController::class, 'register']);
$router->addRoute('POST', '/login', [AuthController::class, 'login']);
$router->addRoute('POST', '/logout', [AuthController::class, 'logout']);



?>