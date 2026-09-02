<?php

use app\App\AutoLoader;
use app\App\Database;
use app\Domain\Article;
use app\Repository\ArticleRepository;

require_once __DIR__ . '/../../app/App/AutoLoader.php';
AutoLoader::loadClass();

function testSave()
{
    $pdo = Database::getConnection();
    $articleRepo = new ArticleRepository($pdo);

    $articleRepo->deleteAll();

    $article = new Article();
    $article->id = null;
    $article->title = 'Sepak bola test';
    $article->content = 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Possimus sed explicabo et vero neque perferendis nihil quo cupiditate, omnis pariatur cumque vel iste asperiores laboriosam amet obcaecati veniam culpa eligendi?';
    $article->userId = 20;

    $response = $articleRepo->save($article);

    if (assert($response->id == $article->id)) {
        echo 'testBerhasil';
    }
}

function testUpdateContent()
{
    $pdo = Database::getConnection();
    $articleRepo = new ArticleRepository($pdo);

    $articleRepo->deleteAll();

    $article = new Article();
    $article->id = null;
    $article->title = 'Sepak bola test';
    $article->content = 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Possimus sed explicabo et vero neque perferendis nihil quo cupiditate, omnis pariatur cumque vel iste asperiores laboriosam amet obcaecati veniam culpa eligendi?';
    $article->userId = 20;
    $articleRepo->save($article);

    $article = new Article();
    $article->id = 6;
    $article->title = 'Sepak bola test';
    $article->content = 'test';
    $article->userId = 20;
    $result = $articleRepo->updateContent($article);

    if (assert($result->content == $article->content)) {
        echo 'testBerhasil';
    }
}

function testGetAll()
{
    $pdo = Database::getConnection();
    $articleRepo = new ArticleRepository($pdo);

    $articleRepo->deleteAll();

    // $article = new Article();
    // $article->id = null;
    // $article->title = 'Sepak bola test';
    // $article->content = 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Possimus sed explicabo et vero neque perferendis nihil quo cupiditate, omnis pariatur cumque vel iste asperiores laboriosam amet obcaecati veniam culpa eligendi?';
    // $article->userId = 20;
    // $articleRepo->save($article);

    $result = $articleRepo->getAll();

    if (assert($result == false)) {
        echo 'test berhasil';
    }
}

testSave();
