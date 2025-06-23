<?php

use App\Controller\EntryController;
use App\Controller\ExpensesController;
use App\Controller\UserController;
use App\Controller\AuthController;



$router = new Router();

$router->addRoute('GET', '/entradas', [EntryController::class, 'findAll']);
$router->addRoute('POST', '/entradas', [EntryController::class, 'create']);
$router->addRoute('GET', '/entradas/{id}', [EntryController::class, 'findOne']);
$router->addRoute('PUT', '/entradas/{id}', [EntryController::class, 'update']);
$router->addRoute('DELETE', '/entradas/{id}', [EntryController::class, 'delete']);
$router->addRoute('DELETE', '/entradas', [EntryController::class, 'deleteMany']);
$router->addRoute('PUT', '/entradas', [EntryController::class, 'updateMany']);
$router->addRoute('POST', '/entradas/many', [EntryController::class, 'createMany']);

$router->addRoute('GET', '/despesas', [ExpensesController::class, 'findAll']);
$router->addRoute('POST', '/despesas', [ExpensesController::class, 'create']);
$router->addRoute('GET', '/despesas/{id}', [ExpensesController::class, 'findOne']);
$router->addRoute('PUT', '/despesas/{id}', [ExpensesController::class, 'update']);
$router->addRoute('DELETE', '/despesas/{id}', [ExpensesController::class, 'delete']);
$router->addRoute('DELETE', '/despesas', [ExpensesController::class, 'deleteMany']);
$router->addRoute('PUT', '/despesas', [ExpensesController::class, 'updateMany']);
$router->addRoute('POST', '/despesas/many', [ExpensesController::class, 'createMany']);

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