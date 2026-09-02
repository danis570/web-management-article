<?php

session_start();

// Load Class

use app\Controller\ArticleController;
use app\Controller\AuthController;

require_once __DIR__ . '/../app/App/AutoLoader.php';
app\App\AutoLoader::loadClass();

// Register all route
app\App\Router::add('GET', '/', app\Controller\HomeController::class, 'home');
// Auth
app\App\Router::add('GET', '/register', AuthController::class, 'register', ['userNotLogged']);
app\App\Router::add('POST', '/register', AuthController::class, 'postRegister', ['userNotLogged']);
app\App\Router::add('GET', '/login', AuthController::class, 'login', ['userNotLogged']);
app\App\Router::add('POST', '/login', AuthController::class, 'postLogin', ['userNotLogged']);
app\App\Router::add('GET', '/logout', AuthController::class, 'logout', ['userHasLogged']);
// Article
app\App\Router::add('GET', '/article', ArticleController::class, 'article', ['userHasLogged']);
app\App\Router::add('GET', '/article/add', ArticleController::class, 'add', ['userHasLogged']);
app\App\Router::add('POST', '/article/add', ArticleController::class, 'postAdd', ['userHasLogged']);
app\App\Router::add('GET', '/article/edit', ArticleController::class, 'edit', ['userHasLogged']);
app\App\Router::add('POST', '/article/edit', ArticleController::class, 'postEdit', ['userHasLogged']);
app\App\Router::add('POST', '/article/delete', ArticleController::class, 'delete', ['userHasLogged']);


// Run all route
app\App\Router::run();