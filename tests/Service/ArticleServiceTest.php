<?php

use app\App\AutoLoader;
use app\App\Database;
use app\Repository\ArticleRepository;
use app\Service\ArticleService;

require_once __DIR__ . '/../../app/App/AutoLoader.php';

AutoLoader::loadClass();

function testAndUser()
{
    $articleRepository = new ArticleRepository(Database::getConnection());
    $articleService = new ArticleService($articleRepository);

    $result = $articleService->getAndUser();

    foreach ($result as $article) {
        if (strlen($article['content']) > 10) {
            $cutContent = substr($article['content'], 0, 15) . '.....';
            $cutContent . '....' . PHP_EOL;
            $article['content'] = $cutContent;
        }
    }
}

testAndUser();