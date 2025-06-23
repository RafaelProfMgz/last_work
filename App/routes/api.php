<?php

use App\Controller\EntradaController;
use App\Controller\SaidaController;
use App\Controller\UserController;
use App\Controller\AuthController;

$router->addRoute('GET', '/entradas', [EntradaController::class, 'list']);
$router->addRoute('POST', '/entradas', [EntradaController::class, 'create']);
$router->addRoute('GET', '/entradas/{id}', [EntradaController::class, 'read']);
$router->addRoute('PUT', '/entradas/{id}', [EntradaController::class, 'update']);
$router->addRoute('DELETE', '/entradas/{id}', [EntradaController::class, 'delete']);

$router->addRoute('GET', '/saidas', [SaidaController::class, 'list']);
$router->addRoute('POST', '/saidas', [SaidaController::class, 'create']);
$router->addRoute('GET', '/saidas/{id}', [SaidaController::class, 'read']);
$router->addRoute('PUT', '/saidas/{id}', [SaidaController::class, 'update']);
$router->addRoute('DELETE', '/saidas/{id}', [SaidaController::class, 'delete']);

$router->addRoute('POST', '/users', [UserController::class, 'create']);
$router->addRoute('GET', '/users', [UserController::class, 'list']);
$router->addRoute('GET', '/users/{id}', [UserController::class, 'read']);
$router->addRoute('PUT', '/users/{id}', [UserController::class, 'update']);
$router->addRoute('DELETE', '/users/{id}', [UserController::class, 'delete']);

$router->addRoute('POST', '/register', [AuthController::class, 'register']);
$router->addRoute('POST', '/login', [AuthController::class, 'login']);
$router->addRoute('POST', '/logout', [AuthController::class, 'logout']);



?>