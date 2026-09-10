<?php

namespace app\Controller;

use app\App\Database;
use app\App\View;
use app\Model\ArticleAddRequest;
use app\Model\ArticleEditRequest;
use app\Repository\ArticleImageRepository;
use app\Repository\ArticleLikeRepository;
use app\Repository\ArticleRepository;
use app\Repository\ArticleTagRepository;
use app\Repository\ArticleUserRepository;
use app\Repository\ArticleViewRepository;
use app\Repository\CommentRepository;
use app\Repository\ProfileRepository;
use app\Repository\TagRepository;
use app\Repository\UserRepository;
use app\Service\ArticleImageService;
use app\Service\ArticleLikeService;
use app\Service\ArticleService;
use app\Service\ArticleTagService;
use app\Service\ArticleUserService;
use app\Service\ArticleViewService;
use app\Service\CommentService;
use app\Service\ProfileService;
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
    private ArticleViewService $articleViewService;
    private ArticleLikeService $articleLikeService;
    private ProfileService $profileService;
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

        $commentRepository = new CommentRepository($pdo);

        $this->commentService = new CommentService(
            $commentRepository
        );
        $articleViewRepository = new ArticleViewRepository();
        $this->articleViewService = new ArticleViewService($articleViewRepository);

        $articleLikeRepository = new ArticleLikeRepository();
        $this->articleLikeService = new ArticleLikeService($articleLikeRepository);

        $profileRepository = new ProfileRepository($pdo);
        $this->profileService = new ProfileService($profileRepository);

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

        // Ambil profile user yang sedang login
        $profile = $this->profileService->getByUserId($user->id);

        // Kirim nama user ke view
        $data['currentUserName'] = $profile->name;

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

            /*
             * =========================
             * VISITOR ID
             * =========================
             */

            $visitorId = $this->getVisitorId();

            $userId = !empty($_SESSION['user_id'])
                ? (int) $_SESSION['user_id']
                : null;


            /*
             * =========================
             * ARTICLE VIEW
             * =========================
             */

            $isNewView = $this->articleViewService->add(
                (int) $article['id'],
                $visitorId,
                $userId
            );

            if ($isNewView) {

                $this->articleService->incrementViewCount(
                    (int) $article['id']
                );

                // Agar angka yang dikirim ke view langsung bertambah
                $article['view_count']++;
            }


            /*
             * =========================
             * ARTICLE LIKE
             * =========================
             */

            $article['is_liked'] = $this->articleLikeService->isLiked(
                (int) $article['id'],
                $visitorId
            );


            /*
             * =========================
             * ARTICLE DATA
             * =========================
             */

            $data['title'] = $article['title'];

            $data['article'] = $article;


            /*
             * =========================
             * ARTICLE IMAGES
             * =========================
             */

            $data['images'] = $this->articleImageService
                ->getByArticleId(
                    (int) $article['id']
                );


            /*
             * =========================
             * COMMENTS
             * =========================
             */

            $commentUserId = (int) ($_SESSION['user_id'] ?? 0);

            $data['comments'] = $this->commentService
                ->getByArticleId(
                    (int) $article['id'],
                    $commentUserId
                );

        } catch (Exception $e) {

            $data['error'] = $e->getMessage();
        }


        /*
         * =========================
         * RENDER
         * =========================
         */

        if ($isLoggedIn) {

            View::renderUser(
                '/Article/detail',
                $data
            );

        } else {

            View::renderPublic(
                '/Article/detail',
                $data
            );
        }
    }

    // Like artikel
    public function like(): void
    {
        try {
            $articleId = (int) ($_POST['article_id'] ?? 0);

            if ($articleId <= 0) {
                throw new Exception('Artikel tidak valid.');
            }

            // Ambil / buat visitor ID
            $visitorId = $this->getVisitorId();

            // User ID jika sedang login
            $userId = !empty($_SESSION['user_id'])
                ? (int) $_SESSION['user_id']
                : null;

            // Tambahkan like
            $isLiked = $this->articleLikeService->like(
                $articleId,
                $visitorId,
                $userId
            );

            // Jika benar-benar like baru
            if ($isLiked) {
                $this->articleService->incrementLikeCount(
                    $articleId
                );
            }

        } catch (Exception $e) {
            // Bisa diganti dengan session flash message nanti
        }

        // Kembali ke halaman artikel sebelumnya
        header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/article'));
        exit();
    }


    // Unlike artikel
    public function unlike(): void
    {
        try {
            $articleId = (int) ($_POST['article_id'] ?? 0);

            if ($articleId <= 0) {
                throw new Exception('Artikel tidak valid.');
            }

            // Visitor ID
            $visitorId = $this->getVisitorId();

            // Hapus like
            $isUnliked = $this->articleLikeService->unlike(
                $articleId,
                $visitorId
            );

            // Jika memang sebelumnya sudah like
            if ($isUnliked) {
                $this->articleService->decrementLikeCount(
                    $articleId
                );
            }

        } catch (Exception $e) {
            // Bisa diganti dengan session flash message nanti
        }

        // Kembali ke halaman artikel sebelumnya
        header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/article'));
        exit();
    }

    private function getVisitorId(): string
    {
        if (!empty($_COOKIE['visitor_id'])) {
            return $_COOKIE['visitor_id'];
        }

        $visitorId = bin2hex(random_bytes(32));

        setcookie(
            'visitor_id',
            $visitorId,
            [
                'expires' => time() + (60 * 60 * 24 * 365),
                'path' => '/',
                'httponly' => true,
                'samesite' => 'Lax',
            ]
        );

        return $visitorId;
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
            $user = $this->userService->getUserByEmail(
                $_SESSION['email']
            );

            // Validasi bahwa user terhubung dengan artikel
            $this->articleUserService->validateUserCanEdit(
                $id,
                $user->id
            );

            $article = $this->articleService->getById($id);

            // Tentukan apakah user yang sedang login adalah owner
            $isOwner = (
                (int) $article['owner_id'] ===
                (int) $user->id
            );

            $images = $this->articleImageService
                ->getByArticleId($id);

            $articleUsers = $this->articleUserService
                ->getUsersByArticleId($id);

            $articleTags = $this->articleTagService
                ->getByArticleId($id);

            View::renderUser('/Article/edit', [
                'title' => 'Edit Article',
                'article' => $article,
                'images' => $images,
                'articleUsers' => $articleUsers,
                'articleTags' => $articleTags,

                'currentUserId' => $user->id,
                'ownerId' => (int) $article['owner_id'],
                'isOwner' => $isOwner
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

            /*
            |--------------------------------------------------------------------------
            | Get Current User
            |--------------------------------------------------------------------------
            */

            $user = $this->userService->getUserByEmail(
                $_SESSION['email']
            );

            $articleId = (int) ($_POST['id'] ?? 0);


            /*
            |--------------------------------------------------------------------------
            | Validate User Can Edit Article
            |--------------------------------------------------------------------------
            */

            $this->articleUserService->validateUserCanEdit(
                $articleId,
                $user->id
            );


            /*
            |--------------------------------------------------------------------------
            | Get Article
            |--------------------------------------------------------------------------
            */

            $article = $this->articleService->getById($articleId);

            $isOwner = (
                (int) $article['owner_id'] ===
                (int) $user->id
            );


            /*
            |--------------------------------------------------------------------------
            | Sync User Article
            |--------------------------------------------------------------------------
            |
            | Hanya owner yang boleh mengubah collaborator.
            |
            */

            if ($isOwner) {

                $selectedUsers = $_POST['selectedUsers'] ?? [];

                if (!is_array($selectedUsers)) {
                    $selectedUsers = [];
                }

                $this->articleUserService->sync(
                    $articleId,
                    $selectedUsers,
                    (int) $user->id,
                    (int) $article['owner_id']
                );
            }


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

                $deletedImageIds = is_array($deleteImages)
                    ? array_map('intval', $deleteImages)
                    : [];

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
                        in_array(
                            $imageId,
                            $deletedImageIds,
                            true
                        )
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

                if (!is_array($captions)) {
                    $captions = [];
                }

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

    public function delete(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        try {
            $user = $this->userService->getUserByEmail(
                $_SESSION['email']
            );

            $articleId = (int) ($_POST['id'] ?? 0);

            if ($articleId <= 0) {
                throw new Exception('Article not found.');
            }

            $article = $this->articleService->getById($articleId);

            // Hanya owner yang boleh menghapus artikel
            if ((int) $article['owner_id'] !== (int) $user->id) {
                throw new Exception(
                    'You are not allowed to delete this article.'
                );
            }

            $this->articleService->deleteById($articleId);

            header('Location: /article');
            exit();

        } catch (Exception $e) {

            header(
                'Location: /article?error=' .
                urlencode($e->getMessage())
            );

            exit();
        }
    }
}