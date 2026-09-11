<?php

session_start();

use app\App\Router;
use app\Controller\ArticleController;
use app\Controller\AuthController;
use app\Controller\CommentController;
use app\Controller\HomeController;
use app\Controller\ProfileController;
use app\Controller\TagController;
use app\Controller\UserController;
use app\Middleware\AdminOnly;
use app\Middleware\GuestOnly;
use app\Middleware\UserAndAdmin;
use app\Middleware\UserOnly;

require_once __DIR__ . '/../app/App/AutoLoader.php';

app\App\AutoLoader::loadClass();


// ============================================================
// HOME
// ============================================================

Router::add('GET', '/', HomeController::class, 'home');


// ============================================================
// AUTH
// ============================================================

// Admin Only
Router::add('GET', '/register', AuthController::class, 'register', [AdminOnly::class]);
Router::add('POST', '/register', AuthController::class, 'postRegister', [AdminOnly::class]);
Router::add('GET', '/users', UserController::class, 'users', [AdminOnly::class]);

// Guest Only
Router::add('GET', '/login', AuthController::class, 'login', [GuestOnly::class]);
Router::add('POST', '/login', AuthController::class, 'postLogin', [GuestOnly::class]);

// User And Admin
Router::add('GET', '/logout', AuthController::class, 'logout', [UserAndAdmin::class]);


// ============================================================
// USER
// ============================================================

Router::add(
    'GET',
    '/profile',
    ProfileController::class,
    'profile',
    [UserAndAdmin::class]
);

Router::add(
    'POST',
    '/profile',
    ProfileController::class,
    'postUpdate',
    [UserAndAdmin::class]
);


Router::add('GET', '/user/search', UserController::class, 'search');


// ============================================================
// ARTICLE
// ============================================================

// Public Article
Router::add('GET', '/article', ArticleController::class, 'article');

// User Article
Router::add('GET', '/article/add', ArticleController::class, 'add', [UserOnly::class]);
Router::add('POST', '/article/add', ArticleController::class, 'postAdd', [UserOnly::class]);

Router::add('GET', '/article/edit', ArticleController::class, 'edit', [UserOnly::class]);
Router::add('POST', '/article/edit', ArticleController::class, 'postEdit', [UserOnly::class]);

Router::add('POST', '/article/delete', ArticleController::class, 'delete', [UserOnly::class]);

Router::add('GET', '/me/article', ArticleController::class, 'myArticle', [UserOnly::class]);

// Article Tag AJAX
Router::add('GET', '/article/tag', ArticleController::class, 'getByTag');

// Article Like
Router::add('POST', '/article/like', ArticleController::class, 'like');
Router::add('POST', '/article/unlike', ArticleController::class, 'unlike');

// Article Detail
Router::add('GET', '/article/{slug}', ArticleController::class, 'detail');


// ============================================================
// TAG
// ============================================================

Router::add('GET', '/tag/search', TagController::class, 'search');


// ============================================================
// COMMENT
// ============================================================

Router::add('POST', '/comment/create', CommentController::class, 'postCreate', [UserOnly::class]);
Router::add('POST', '/comment/update', CommentController::class, 'postUpdate', [UserOnly::class]);
Router::add('POST', '/comment/delete', CommentController::class, 'postDelete', [UserOnly::class]);
Router::add('POST', '/comment/like', CommentController::class, 'postLike', [UserOnly::class]);
Router::add('POST', '/comment/unlike', CommentController::class, 'postUnlike', [UserOnly::class]);


// ============================================================
// RUN ROUTER
// ============================================================

Router::run();
