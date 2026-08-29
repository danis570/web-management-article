<?php

// Load Class

use app\Controller\AuthController;

require_once __DIR__ . '/../app/App/AutoLoader.php';
app\App\AutoLoader::loadClass();

// Register all route
app\App\Router::add('GET', '/', app\Controller\HomeController::class, 'index');
app\App\Router::add('GET', '/login', AuthController::class, 'login');
app\App\Router::add('GET', '/register', AuthController::class, 'register');
app\App\Router::add('POST', '/register', AuthController::class, 'postRegister');
// Run all route
app\App\Router::run();