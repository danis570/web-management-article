<?php

use app\App\AutoLoader;
use app\App\Database;
use app\Domain\Comment;
use app\Repository\ArticleRepository;
use app\Repository\CommentRepository;
use app\Repository\UserRepository;

require_once __DIR__ . '/../../app/App/AutoLoader.php';

AutoLoader::loadClass();

function testSave()
{
    $pdo = Database::getConnection();
    $articleRepository = new ArticleRepository($pdo);
    $userRepository = new UserRepository($pdo);
    $commentRepository = new CommentRepository($pdo);

    // $commentRepository->deleteAll();

    $article = $articleRepository->getById(20);
    $user = $userRepository->findByEmail('ahmad56danish@gmail.com');

    $comment = new Comment();
    $comment->content = 'Comments Test';
    $comment->parentId = 6;
    $comment->articleId = $article['id'];
    $comment->userId = $user->id;

    $result = $commentRepository->save($comment);
    try {
        assert($result->userId == $comment->userId, 'Test Failed');
        echo 'Test Success';
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

testSave();