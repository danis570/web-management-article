<?php

session_start();

// Load Class

use app\Controller\ArticleController;
use app\Controller\AuthController;
use app\Controller\UserController;

require_once __DIR__ . '/../app/App/AutoLoader.php';
app\App\AutoLoader::loadClass();

// Register all route
app\App\Router::add('GET', '/', app\Controller\HomeController::class, 'home');

// Auth
// Admin Only
app\App\Router::add('GET', '/register', AuthController::class, 'register', ['adminOnly']);
app\App\Router::add('POST', '/register', AuthController::class, 'postRegister', ['adminOnly']);
app\App\Router::add('GET', '/users', UserController::class, 'users', ['adminOnly']);
// User Only
app\App\Router::add('GET', '/login', AuthController::class, 'login', ['guestOnly']);
app\App\Router::add('POST', '/login', AuthController::class, 'postLogin', ['guestOnly']);
// User And Admin
app\App\Router::add('GET', '/logout', AuthController::class, 'logout', ['userAndAdmin']);

// Article
app\App\Router::add('GET', '/article', ArticleController::class, 'article');
app\App\Router::add('GET', '/article/add', ArticleController::class, 'add', ['userOnly']);
app\App\Router::add('POST', '/article/add', ArticleController::class, 'postAdd', ['userOnly']);
app\App\Router::add('GET', '/article/edit', ArticleController::class, 'edit', ['userOnly']);
app\App\Router::add('POST', '/article/edit', ArticleController::class, 'postEdit', ['userOnly']);
app\App\Router::add('POST', '/article/delete', ArticleController::class, 'delete', ['userOnly']);

app\App\Router::add('GET', '/article/detail', ArticleController::class, 'detail', ['guestOnly']);

// Run all route
app\App\Router::run();