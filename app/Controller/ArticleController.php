<?php

namespace app\Controller;

use app\App\Database;
use app\App\View;
use app\Model\ArticleAddRequest;
use app\Model\ArticleEditRequest;
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
        if (($_SESSION['login'] ?? false) == true) {
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
        } else {
            try {
                $article = $this->articleService->getAndUser();

                View::renderPublic('/Article/article', [
                    'title' => 'Article',
                    'article' => $article
                ]);
            } catch (Exception $e) {
                View::renderPublic('/Article/article', [
                    'title' => 'Article',
                    'emptyArticle' => $e->getMessage()
                ]);
            }
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

    function edit()
    {
        $id = $_GET['id'] ?? 0;

        $user = $this->userService->getUserByEmail($_SESSION['email']);

        try {
            $article = $this->articleService->getById($id);
            if ($article['id'] != $user['id']) {
                throw new Exception('Not Your Article');
            }

            $article = $this->articleService->getById($id);
            View::renderUser('/Article/edit', [
                'title' => 'Edit Article',
                'article' => $article
            ]);
        } catch (Exception $e) {
            View::renderUser('/Article/edit', [
                'title' => 'Edit Article',
                'error' => $e->getMessage()
            ]);
        }

    }

    function postEdit()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $request = new ArticleEditRequest();
                $request->id = $_POST['id'];
                $request->content = $_POST['content'];

                $this->articleService->edit($request);

                header('Location: /article');
            } catch (Exception $e) {
                View::renderUser('/Article/edit', [
                    'title' => 'Edit Article',
                    'error' => $e->getMessage()
                ]);
            }
        }
    }

    function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $this->articleService->deleteById($id);
            header('Location: /article');
        }
    }
}