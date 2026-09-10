<?php

session_start();

use app\Controller\ArticleController;
use app\Controller\AuthController;
use app\Controller\CommentController;
use app\Controller\TagController;
use app\Controller\UserController;

require_once __DIR__ . '/../app/App/AutoLoader.php';

app\App\AutoLoader::loadClass();


// ============================================================
// HOME
// ============================================================

app\App\Router::add(
    'GET',
    '/',
    app\Controller\HomeController::class,
    'home'
);


// ============================================================
// AUTH
// ============================================================

// -------------------------
// Admin Only
// -------------------------

app\App\Router::add(
    'GET',
    '/register',
    AuthController::class,
    'register',
    ['adminOnly']
);

app\App\Router::add(
    'POST',
    '/register',
    AuthController::class,
    'postRegister',
    ['adminOnly']
);

app\App\Router::add(
    'GET',
    '/users',
    UserController::class,
    'users',
    ['adminOnly']
);


// -------------------------
// Guest Only
// -------------------------

app\App\Router::add(
    'GET',
    '/login',
    AuthController::class,
    'login',
    ['guestOnly']
);

app\App\Router::add(
    'POST',
    '/login',
    AuthController::class,
    'postLogin',
    ['guestOnly']
);


// -------------------------
// User And Admin
// -------------------------

app\App\Router::add(
    'GET',
    '/logout',
    AuthController::class,
    'logout',
    ['userAndAdmin']
);


// ============================================================
// USER
// ============================================================

app\App\Router::add(
    'GET',
    '/user/search',
    UserController::class,
    'search'
);


// ============================================================
// ARTICLE
// ============================================================

// -------------------------
// Public Article
// -------------------------

// Semua artikel
app\App\Router::add(
    'GET',
    '/article',
    ArticleController::class,
    'article'
);


// -------------------------
// User Article
// -------------------------

// Tambah artikel
app\App\Router::add(
    'GET',
    '/article/add',
    ArticleController::class,
    'add',
    ['userOnly']
);

app\App\Router::add(
    'POST',
    '/article/add',
    ArticleController::class,
    'postAdd',
    ['userOnly']
);


// Edit artikel
app\App\Router::add(
    'GET',
    '/article/edit',
    ArticleController::class,
    'edit',
    ['userOnly']
);

app\App\Router::add(
    'POST',
    '/article/edit',
    ArticleController::class,
    'postEdit',
    ['userOnly']
);


// Hapus artikel
app\App\Router::add(
    'POST',
    '/article/delete',
    ArticleController::class,
    'delete',
    ['userOnly']
);


// Artikel milik user
app\App\Router::add(
    'GET',
    '/me/article',
    ArticleController::class,
    'myArticle',
    ['userOnly']
);


// -------------------------
// Article Tag AJAX
// -------------------------

app\App\Router::add(
    'GET',
    '/article/tag',
    ArticleController::class,
    'getByTag'
);

// =========================
// Article Like
// =========================

app\App\Router::add(
    'POST',
    '/article/like',
    ArticleController::class,
    'like'
);

app\App\Router::add(
    'POST',
    '/article/unlike',
    ArticleController::class,
    'unlike'
);


// -------------------------
// Article Detail
// -------------------------

app\App\Router::add(
    'GET',
    '/article/{slug}',
    ArticleController::class,
    'detail'
);


// ============================================================
// TAG
// ============================================================

// Search tag
app\App\Router::add(
    'GET',
    '/tag/search',
    TagController::class,
    'search'
);


// ============================================================
// COMMENT
// ============================================================

// Create comment
app\App\Router::add(
    'POST',
    '/comment/create',
    CommentController::class,
    'postCreate',
    ['userOnly']
);


// Update comment
app\App\Router::add(
    'POST',
    '/comment/update',
    CommentController::class,
    'postUpdate',
    ['userOnly']
);


// Delete comment
app\App\Router::add(
    'POST',
    '/comment/delete',
    CommentController::class,
    'postDelete',
    ['userOnly']
);


// Like comment
app\App\Router::add(
    'POST',
    '/comment/like',
    CommentController::class,
    'postLike',
    ['userOnly']
);


// Unlike comment
app\App\Router::add(
    'POST',
    '/comment/unlike',
    CommentController::class,
    'postUnlike',
    ['userOnly']
);


// ============================================================
// RUN ROUTER
// ============================================================

app\App\Router::run();