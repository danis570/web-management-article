<?php

namespace app\Controller;

use app\App\Database;
use app\App\View;
use app\Model\ArticleAddRequest;
use app\Repository\ArticleRepository;
use app\Repository\UserRepository;
use app\Service\ArticleService;
use app\Service\UserService;
use Exception;

class ArticleController
{
    private ArticleService $articleService;

    private UserService $userService;

    public function __construct()
    {
        $pdo = Database::getConnection();
        $articleRepository = new ArticleRepository($pdo);
        $this->articleService = new ArticleService($articleRepository);

        $userRepository = new UserRepository($pdo);
        $this->userService = new UserService($userRepository);
    }

    function article()
    {
        try {
            $user = $this->userService->getUserByEmail($_SESSION['email']);
            $article = $this->articleService->getByUserId($user['id']);

            View::renderUser('/Article/article', [
                'title' => 'Article',
                'article' => $article
            ]);
        } catch (Exception $e) {
            View::renderUser('/Article/article', [
                'title' => 'Article',
                'emptyArticle' => $e->getMessage()
            ]);
        }
    }

    function add()
    {
        View::renderUser('/Article/add', [
            'title' => 'Add new Article',
        ]);
    }

    function postAdd()
    {
        $user = $this->userService->getUserByEmail($_SESSION['email']);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $request = new ArticleAddRequest();
            $request->title = $_POST['title'];
            $request->content = $_POST['content'];
            $request->userId = $user['id'];

            try {
                $this->articleService->add($request);
                header('Location: /article');
            } catch (Exception $e) {
                View::renderUser('/Article/add', [
                    'title' => 'Add new Article',
                    'error' => $e->getMessage()
                ]);
            }
        }
    }
}