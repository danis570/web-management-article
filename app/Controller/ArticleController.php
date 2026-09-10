<?php

namespace app\Controller;

use app\App\Database;
use app\App\View;
use app\Model\ArticleAddRequest;
use app\Model\ArticleEditRequest;
use app\Repository\ArticleImageRepository;
use app\Repository\ArticleRepository;
use app\Repository\ArticleTagRepository;
use app\Repository\ArticleUserRepository;
use app\Repository\CommentRepository;
use app\Repository\TagRepository;
use app\Repository\UserRepository;
use app\Service\ArticleImageService;
use app\Service\ArticleService;
use app\Service\ArticleTagService;
use app\Service\ArticleUserService;
use app\Service\CommentService;
use app\Service\TagService;
use app\Service\UserService;
use Exception;

class ArticleController
{
    private ArticleService $articleService;

    private ArticleImageService $articleImageService;
    private ArticleUserService $articleUserService;
    private TagService $tagService;
    private ArticleTagService $articleTagService;

    private UserService $userService;

    private CommentService $commentService;
    public function __construct()
    {
        $pdo = Database::getConnection();

        $articleRepository = new ArticleRepository($pdo);
        $this->articleService = new ArticleService($articleRepository);

        $articleUserRepository = new ArticleUserRepository($pdo);
        $this->articleUserService = new ArticleUserService(
            $articleUserRepository
        );

        $tagRepository = new TagRepository($pdo);
        $this->tagService = new TagService($tagRepository);

        $articleTagRepository = new ArticleTagRepository($pdo);
        $this->articleTagService = new ArticleTagService($articleTagRepository);

        $articleImageRepository = new ArticleImageRepository($pdo);
        $this->articleImageService = new ArticleImageService(
            $articleImageRepository
        );

        $tagRepository = new TagRepository($pdo);

        $commentRepository = new CommentRepository($pdo);

        $this->commentService = new CommentService(
            $commentRepository
        );

        $userRepository = new UserRepository($pdo);
        $this->userService = new UserService($userRepository);
    }

    public function article(): void
    {
        // Admin tidak boleh mengakses halaman artikel user
        if ($_SESSION['admin'] ?? false) {
            header('Location: /');
            exit();
        }

        $isLoggedIn = $_SESSION['login'] ?? false;
        $data = [
            'title' => 'Article',
            'current' => 'article',
        ];

        try {
            // Ambil semua artikel
            $data['article'] = $this->articleService->getAndUser();
        } catch (Exception $e) {
            $data['emptyArticle'] = $e->getMessage();
        }

        // Render view berdasarkan status login
        if ($isLoggedIn) {
            View::renderUser('/Article/article', $data);
        } else {
            View::renderPublic('/Article/article', $data);
        }
    }

    public function myArticle(): void
    {
        // Admin tidak boleh mengakses halaman artikel user
        if ($_SESSION['admin'] ?? false) {
            header('Location: /');
            exit();
        }

        // Harus login
        if (!($_SESSION['login'] ?? false)) {
            header('Location: /login');
            exit();
        }

        $data = [
            'title' => 'My Article',
            'current' => 'my-article',
        ];

        try {
            $user = $this->userService->getUserByEmail($_SESSION['email']);
            $data['article'] = $this->articleService->getByUserId($user->id);
        } catch (Exception $e) {
            $data['emptyArticle'] = $e->getMessage();
        }

        View::renderUser('/Article/me', $data);
    }
    public function detail(array $params): void
    {
        $slug = $params['slug'] ?? '';
        $isLoggedIn = $_SESSION['login'] ?? false;

        $data = [
            'title' => 'Article Detail',
            'current' => 'article',
        ];

        try {
            $article = $this->articleService->getBySlug($slug);

            if (!$article) {
                throw new Exception('Article not found.');
            }

            $data['title'] = $article['title'];
            $data['article'] = $article;

            $data['images'] = $this->articleImageService
                ->getByArticleId($article['id']);

            $userId = (int) ($_SESSION['user_id'] ?? 0);

            $data['comments'] = $this->commentService
                ->getByArticleId(
                    $article['id'],
                    $userId
                );

        } catch (Exception $e) {
            $data['error'] = $e->getMessage();
        }

        if ($isLoggedIn) {
            View::renderUser('/Article/detail', $data);
        } else {
            View::renderPublic('/Article/detail', $data);
        }
    }

    public function tag(array $params): void
    {
        $tagSlug = $params['slug'] ?? '';

        $data = [
            'title' => 'Artikel',
            'current' => 'article',
            'tag' => null,
            'articles' => [],
        ];

        try {

            $tag = $this->tagService->getBySlug($tagSlug);

            if (!$tag) {
                throw new Exception('Tag not found.');
            }

            $data['tag'] = $tag;

            $data['articles'] =
                $this->articleService->getByTag(
                    $tag->slug
                );

        } catch (Exception $e) {

            $data['error'] = $e->getMessage();
        }

        View::renderUser(
            '/Article/tag',
            $data
        );
    }

    public function getByTag(): void
    {
        header('Content-Type: application/json');

        $tagSlug = trim($_GET['slug'] ?? '');

        if ($tagSlug === '') {
            echo json_encode([
                'success' => false,
                'message' => 'Tag slug is required.'
            ]);
            return;
        }

        try {
            $tag = $this->tagService->getBySlug($tagSlug);

            if (!$tag) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Tag not found.'
                ]);
                return;
            }

            $articles = $this->articleService->getByTag($tag->slug);

            echo json_encode([
                'success' => true,
                'tag' => [
                    'name' => $tag->name,
                    'slug' => $tag->slug,
                ],
                'articles' => $articles
            ]);

        } catch (Exception $e) {

            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
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

            // Tambahkan tag artikel
            $selectedTags = $_POST['selectedTags'] ?? [];

            if (!is_array($selectedTags)) {
                $selectedTags = [];
            }

            $this->articleTagService->sync(
                $article->id,
                $selectedTags
            );

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

            header('Location: /me/article');
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

            // Validasi hak akses
            $this->articleUserService->validateUserCanEdit(
                $id,
                $user->id
            );

            $article = $this->articleService->getById($id);

            $images = $this->articleImageService->getByArticleId($id);

            $articleUsers = $this->articleUserService->getUsersByArticleId($id);

            $articleTags = $this->articleTagService->getByArticleId($id);

            View::renderUser('/Article/edit', [
                'title' => 'Edit Article',
                'article' => $article,
                'images' => $images,
                'articleUsers' => $articleUsers,
                'articleTags' => $articleTags,
                'currentUserId' => $user->id
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
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        try {

            $user = $this->userService->getUserByEmail(
                $_SESSION['email']
            );

            $articleId = (int) ($_POST['id'] ?? 0);

            $this->articleUserService->validateUserCanEdit(
                $articleId,
                $user->id
            );

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
            | Sync Tag Article
            |--------------------------------------------------------------------------
            */

            $selectedTags = $_POST['selectedTags'] ?? [];

            if (!is_array($selectedTags)) {
                $selectedTags = [];
            }

            $this->articleTagService->sync(
                $articleId,
                $selectedTags
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

            header('Location: /me/article');
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