<?php

namespace app\Controller;

use app\App\Database;
use app\App\View;
use app\Model\ArticleAddRequest;
use app\Model\ArticleEditRequest;
use app\Repository\ArticleImageRepository;
use app\Repository\ArticleRepository;
use app\Repository\ArticleUserRepository;
use app\Repository\UserRepository;
use app\Service\ArticleImageService;
use app\Service\ArticleService;
use app\Service\ArticleUserService;
use app\Service\UserService;
use Exception;

class ArticleController
{
    private ArticleService $articleService;

    private ArticleImageService $articleImageService;
    private ArticleUserService $articleUserService;

    private UserService $userService;

    public function __construct()
    {
        $pdo = Database::getConnection();

        $articleRepository = new ArticleRepository($pdo);
        $this->articleService = new ArticleService($articleRepository);

        $articleUserRepository = new ArticleUserRepository($pdo);
        $this->articleUserService = new ArticleUserService(
            $articleUserRepository
        );

        $articleImageRepository = new ArticleImageRepository($pdo);
        $this->articleImageService = new ArticleImageService(
            $articleImageRepository
        );

        $userRepository = new UserRepository($pdo);
        $this->userService = new UserService($userRepository);
    }

    function article()
    {
        // Admin tidak boleh mengakses halaman artikel user
        if (($_SESSION['admin'] ?? false) == true) {
            header('Location: /');
            exit();
        }

        // Jika user sudah login
        if (($_SESSION['login'] ?? false) == true) {

            try {

                $user = $this->userService->getUserByEmail(
                    $_SESSION['email']
                );

                $article = $this->articleService->getByUserId(
                    $user->id
                );

                View::renderUser('/Article/article', [
                    'title' => 'Article',
                    'current' => 'article',
                    'article' => $article
                ]);

            } catch (Exception $e) {

                View::renderUser('/Article/article', [
                    'title' => 'Article',
                    'current' => 'article',
                    'emptyArticle' => $e->getMessage()
                ]);
            }

        } else {

            // Public
            try {

                $article = $this->articleService->getAndUser();

                View::renderPublic('/Article/article', [
                    'title' => 'Article',
                    'current' => 'article',
                    'article' => $article
                ]);

            } catch (Exception $e) {

                View::renderPublic('/Article/article', [
                    'title' => 'Article',
                    'current' => 'article',
                    'emptyArticle' => $e->getMessage()
                ]);
            }
        }
    }

    function detail()
    {
        $id = $_GET['id'] ?? 0;

        try {

            $article = $this->articleService->getById((int) $id);

            // Ambil semua gambar artikel
            $images = $this->articleImageService->getByArticleId(
                (int) $id
            );

            View::renderPublic('/Article/detail', [
                'title' => $article['title'],
                'current' => 'article',
                'article' => $article,
                'images' => $images
            ]);

        } catch (Exception $e) {

            View::renderPublic('/Article/detail', [
                'title' => 'Article Detail',
                'current' => 'article',
                'error' => $e->getMessage()
            ]);
        }
    }

    function add()
    {
        View::renderUser('/Article/add', [
            'title' => 'Add new Article'
        ]);
    }

    public function postAdd()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        try {
            $user = $this->userService->getUserByEmail($_SESSION['email']);

            $request = new ArticleAddRequest();
            $request->title = trim($_POST['title'] ?? '');
            $request->content = trim($_POST['content'] ?? '');

            $request->userId = (int) ($user->id ?? 0);

            $response = $this->articleService->add($request);
            $article = $response->article;

            // Tambahkan pembuat artikel sebagai user pertama
            $this->articleUserService->add(
                $article->id,
                $user->id
            );

            // Tambahkan user lain yang dipilih
            $selectedUsers = $_POST['selectedUsers'] ?? [];

            if (is_array($selectedUsers)) {
                foreach ($selectedUsers as $userId) {

                    $userId = (int) $userId;

                    // Jangan tambahkan diri sendiri lagi
                    if ($userId <= 0 || $userId === $user->id) {
                        continue;
                    }

                    $this->articleUserService->add(
                        $article->id,
                        $userId
                    );
                }
            }

            if (isset($_FILES['images']['name']) && is_array($_FILES['images']['name'])) {

                $captions = $_POST['captions'] ?? [];

                foreach ($_FILES['images']['name'] as $key => $name) {

                    // Lewati jika tidak ada file
                    if ($_FILES['images']['error'][$key] === UPLOAD_ERR_NO_FILE) {
                        continue;
                    }

                    // Buat struktur file
                    $file = [
                        'name' => $_FILES['images']['name'][$key],
                        'type' => $_FILES['images']['type'][$key],
                        'tmp_name' => $_FILES['images']['tmp_name'][$key],
                        'error' => $_FILES['images']['error'][$key],
                        'size' => $_FILES['images']['size'][$key],
                    ];

                    // Ambil caption berdasarkan index gambar
                    $caption = trim($captions[$key] ?? '');

                    // Simpan gambar + caption
                    $this->articleImageService->add(
                        $article->id,
                        $file,
                        $caption
                    );
                }
            }

            $_SESSION['flash_message'] = 'Success add new article';

            header('Location: /article');
            exit();

        } catch (Exception $e) {

            View::renderUser('/Article/add', [
                'title' => 'Add new Article',
                'error' => $e->getMessage()
            ]);
        }
    }

    function edit()
    {
        $id = (int) ($_GET['id'] ?? 0);
        try {
            $user = $this->userService->getUserByEmail($_SESSION['email']);
            $article = $this->articleService->getById($id);
            $images = $this->articleImageService->getByArticleId($id);
            $articleUsers = $this->articleUserService->getUsersByArticleId($id);
            View::renderUser('/Article/edit', ['title' => 'Edit Article', 'article' => $article, 'images' => $images, 'articleUsers' => $articleUsers, 'currentUserId' => $user->id]);
        } catch (Exception $e) {
            View::renderUser('/Article/edit', ['title' => 'Edit Article', 'error' => $e->getMessage()]);
        }
    }
    function postEdit()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        try {

            $user = $this->userService->getUserByEmail(
                $_SESSION['email']
            );

            $articleId = (int) ($_POST['id'] ?? 0);


            /*
            |--------------------------------------------------------------------------
            | Update Article
            |--------------------------------------------------------------------------
            */

            $request = new ArticleEditRequest();

            $request->id = $articleId;
            $request->title = trim($_POST['title'] ?? '');
            $request->content = trim($_POST['content'] ?? '');

            $this->articleService->edit($request);


            /*
            |--------------------------------------------------------------------------
            | Sync User Article
            |--------------------------------------------------------------------------
            */

            $selectedUsers = $_POST['selectedUsers'] ?? [];

            if (!is_array($selectedUsers)) {
                $selectedUsers = [];
            }

            /*
             * Pastikan pembuat/editor tetap memiliki akses
             * ke artikel.
             */
            $selectedUsers[] = $user->id;

            $this->articleUserService->sync(
                $articleId,
                $selectedUsers
            );


            /*
            |--------------------------------------------------------------------------
            | Hapus Gambar Lama
            |--------------------------------------------------------------------------
            */

            $deleteImages = $_POST['deleteImages'] ?? [];

            if (is_array($deleteImages)) {

                foreach ($deleteImages as $imageId) {

                    $imageId = (int) $imageId;

                    if ($imageId <= 0) {
                        continue;
                    }

                    $this->articleImageService
                        ->deleteById($imageId);
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Update Caption Gambar Lama
            |--------------------------------------------------------------------------
            */

            $existingCaptions = $_POST['existingCaptions'] ?? [];

            if (is_array($existingCaptions)) {

                foreach ($existingCaptions as $imageId => $caption) {

                    $imageId = (int) $imageId;

                    if ($imageId <= 0) {
                        continue;
                    }

                    /*
                     * Jangan update caption gambar
                     * yang sedang dihapus.
                     */
                    if (
                        is_array($deleteImages) &&
                        in_array($imageId, array_map('intval', $deleteImages), true)
                    ) {
                        continue;
                    }

                    $this->articleImageService
                        ->updateCaption(
                            $imageId,
                            trim($caption)
                        );
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Upload Gambar Baru
            |--------------------------------------------------------------------------
            */

            if (
                isset($_FILES['images']['name']) &&
                is_array($_FILES['images']['name'])
            ) {

                $captions = $_POST['captions'] ?? [];

                foreach (
                    $_FILES['images']['name'] as $key => $name
                ) {

                    if (
                        $_FILES['images']['error'][$key]
                        === UPLOAD_ERR_NO_FILE
                    ) {
                        continue;
                    }

                    $file = [
                        'name' => $_FILES['images']['name'][$key],
                        'type' => $_FILES['images']['type'][$key],
                        'tmp_name' => $_FILES['images']['tmp_name'][$key],
                        'error' => $_FILES['images']['error'][$key],
                        'size' => $_FILES['images']['size'][$key],
                    ];

                    $caption = trim(
                        $captions[$key] ?? ''
                    );

                    $this->articleImageService->add(
                        $articleId,
                        $file,
                        $caption
                    );
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Success
            |--------------------------------------------------------------------------
            */

            $_SESSION['flash_message'] =
                'Success edit article';

            header('Location: /article');
            exit();

        } catch (Exception $e) {

            View::renderUser('/Article/edit', [
                'title' => 'Edit Article',
                'error' => $e->getMessage()
            ]);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE ARTICLE
    |--------------------------------------------------------------------------
    */

    function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            return;
        }

        try {

            $id = (int) ($_POST['id'] ?? 0);

            $this->articleService->deleteById($id);

            header('Location: /article');
            exit();

        } catch (Exception $e) {

            header('Location: /article?error=' . urlencode($e->getMessage()));
            exit();
        }
    }
}