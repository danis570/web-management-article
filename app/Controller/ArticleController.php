<?php

namespace app\Controller;

use app\App\Database;
use app\App\View;
use app\Domain\UserRole;
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
use app\Repository\SessionRepository;
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
use app\Service\SessionService;
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
    private SessionService $sessionService;

    public function __construct()
    {
        $pdo = Database::getConnection();

        /*
        |--------------------------------------------------------------------------
        | Article
        |--------------------------------------------------------------------------
        */

        $articleRepository = new ArticleRepository($pdo);
        $userRepository = new UserRepository($pdo);
        $this->articleService = new ArticleService(
            $articleRepository,
            $userRepository
        );


        /*
        |--------------------------------------------------------------------------
        | Article User
        |--------------------------------------------------------------------------
        */

        $articleUserRepository = new ArticleUserRepository($pdo);

        $this->articleUserService = new ArticleUserService(
            $articleUserRepository
        );


        /*
        |--------------------------------------------------------------------------
        | Article Tag
        |--------------------------------------------------------------------------
        */

        $tagRepository = new TagRepository($pdo);

        $this->tagService = new TagService(
            $tagRepository
        );

        $articleTagRepository = new ArticleTagRepository($pdo);

        $this->articleTagService = new ArticleTagService(
            $articleTagRepository
        );


        /*
        |--------------------------------------------------------------------------
        | Article Image
        |--------------------------------------------------------------------------
        */

        $articleImageRepository = new ArticleImageRepository($pdo);

        $this->articleImageService = new ArticleImageService(
            $articleImageRepository
        );


        /*
        |--------------------------------------------------------------------------
        | Comment
        |--------------------------------------------------------------------------
        */

        $commentRepository = new CommentRepository($pdo);

        $this->commentService = new CommentService(
            $commentRepository
        );


        /*
        |--------------------------------------------------------------------------
        | Article View
        |--------------------------------------------------------------------------
        */

        $articleViewRepository = new ArticleViewRepository();

        $this->articleViewService = new ArticleViewService(
            $articleViewRepository
        );


        /*
        |--------------------------------------------------------------------------
        | Article Like
        |--------------------------------------------------------------------------
        */

        $articleLikeRepository = new ArticleLikeRepository();

        $this->articleLikeService = new ArticleLikeService(
            $articleLikeRepository
        );


        /*
        |--------------------------------------------------------------------------
        | Profile
        |--------------------------------------------------------------------------
        */

        $profileRepository = new ProfileRepository($pdo);

        $this->profileService = new ProfileService(
            $profileRepository
        );


        /*
        |--------------------------------------------------------------------------
        | User
        |--------------------------------------------------------------------------
        */

        $this->userService = new UserService(
            $userRepository
        );


        /*
        |--------------------------------------------------------------------------
        | Session
        |--------------------------------------------------------------------------
        */

        $sessionRepository = new SessionRepository($pdo);

        $this->sessionService = new SessionService(
            $sessionRepository
        );
    }


    /*
    |--------------------------------------------------------------------------
    | PUBLIC ARTICLE
    |--------------------------------------------------------------------------
    */

    public function article(): void
    {
        $data = [
            'title' => 'Article',
            'current' => 'article',
        ];

        /*
         * Halaman artikel tidak boleh diakses admin.
         *
         * Route ini public, jadi pengecekan admin
         * tetap dilakukan di controller.
         */

        $user = $this->getCurrentUser();

        if (
            $user !== null &&
            $user->role === UserRole::ADMIN
        ) {
            header('Location: /');
            exit();
        }


        try {

            $data['article'] =
                $this->articleService->getAndUser();

        } catch (Exception $e) {

            $data['emptyArticle'] = $e->getMessage();
        }


        /*
         * Render berdasarkan status login.
         */

        if ($user !== null) {

            View::renderUser(
                '/Article/article',
                $data
            );

            return;
        }

        View::renderPublic(
            '/Article/article',
            $data
        );
    }


    /*
    |--------------------------------------------------------------------------
    | MY ARTICLE
    |--------------------------------------------------------------------------
    */

    public function myArticle(): void
    {
        /*
         * Route sudah menggunakan UserOnly.
         *
         * Jadi di sini kita tidak perlu lagi
         * mengecek $_SESSION['login'] atau $_SESSION['admin'].
         */

        $userId = $this->getCurrentUserId();

        if ($userId === null) {
            header('Location: /login');
            exit();
        }


        $data = [
            'title' => 'My Article',
            'current' => 'my-article',
        ];


        try {

            $user = $this->userService->getUserById(
                $userId
            );

            $data['article'] =
                $this->articleService->getByUserId(
                    $user->id
                );


            /*
             * Profile user yang sedang login.
             */

            $profile =
                $this->profileService->getByUserId(
                    $user->id
                );

            $data['currentUserName'] =
                $profile->name;

        } catch (Exception $e) {

            $data['emptyArticle'] =
                $e->getMessage();
        }


        View::renderUser(
            '/Article/me',
            $data
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ARTICLE DETAIL
    |--------------------------------------------------------------------------
    */

    public function detail(array $params): void
    {
        $slug = trim($params['slug'] ?? '');

        /*
         * Ambil user sekali saja.
         */

        $user = $this->getCurrentUser();

        $data = [
            'title' => 'Article Detail',
            'current' => 'article',
            'isLoggedIn' => $user !== null,
            'currentUserId' => $user?->id
        ];


        try {

            /*
             * Ambil artikel.
             */

            $article =
                $this->articleService->getBySlug(
                    $slug
                );

            if (!$article) {
                throw new Exception(
                    'Article not found.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Visitor
            |--------------------------------------------------------------------------
            */

            $visitorId =
                $this->getVisitorId();


            /*
            |--------------------------------------------------------------------------
            | User
            |--------------------------------------------------------------------------
            */

            $userId = $user?->id;


            /*
            |--------------------------------------------------------------------------
            | Article View
            |--------------------------------------------------------------------------
            */

            $isNewView =
                $this->articleViewService->add(
                    (int) $article['id'],
                    $visitorId,
                    $userId
                );


            if ($isNewView) {

                $this->articleService->incrementViewCount(
                    (int) $article['id']
                );

                /*
                 * Supaya angka view yang dikirim ke view
                 * langsung bertambah.
                 */

                $article['view_count']++;
            }


            /*
            |--------------------------------------------------------------------------
            | Article Like
            |--------------------------------------------------------------------------
            */

            $article['is_liked'] =
                $this->articleLikeService->isLiked(
                    (int) $article['id'],
                    $visitorId
                );


            /*
            |--------------------------------------------------------------------------
            | Article
            |--------------------------------------------------------------------------
            */

            $data['title'] =
                $article['title'];

            $data['article'] =
                $article;


            /*
            |--------------------------------------------------------------------------
            | Images
            |--------------------------------------------------------------------------
            */

            $data['images'] =
                $this->articleImageService->getByArticleId(
                    (int) $article['id']
                );


            /*
            |--------------------------------------------------------------------------
            | Comments
            |--------------------------------------------------------------------------
            */

            $commentUserId =
                $user?->id ?? 0;

            $data['comments'] =
                $this->commentService->getByArticleId(
                    (int) $article['id'],
                    $commentUserId
                );

        } catch (Exception $e) {

            $data['error'] =
                $e->getMessage();
        }


        /*
        |--------------------------------------------------------------------------
        | Render
        |--------------------------------------------------------------------------
        */

        if ($user !== null) {

            View::renderUser(
                '/Article/detail',
                $data
            );

            return;
        }

        View::renderPublic(
            '/Article/detail',
            $data
        );
    }


    /*
    |--------------------------------------------------------------------------
    | LIKE ARTICLE
    |--------------------------------------------------------------------------
    */

    public function like(): void
    {
        try {

            $articleId =
                (int) ($_POST['article_id'] ?? 0);

            if ($articleId <= 0) {
                throw new Exception(
                    'Artikel tidak valid.'
                );
            }


            /*
             * Visitor ID.
             */

            $visitorId =
                $this->getVisitorId();


            /*
             * User ID jika login.
             */

            $userId =
                $this->getCurrentUserId();


            /*
             * Simpan like.
             */

            $isLiked =
                $this->articleLikeService->like(
                    $articleId,
                    $visitorId,
                    $userId
                );


            /*
             * Hanya increment jika benar-benar
             * like baru.
             */

            if ($isLiked) {

                $this->articleService->incrementLikeCount(
                    $articleId
                );
            }

        } catch (Exception $e) {

            /*
             * Nanti bisa diganti flash message.
             */
        }


        header(
            'Location: ' .
            ($_SERVER['HTTP_REFERER'] ?? '/article')
        );

        exit();
    }


    /*
    |--------------------------------------------------------------------------
    | UNLIKE ARTICLE
    |--------------------------------------------------------------------------
    */

    public function unlike(): void
    {
        try {

            $articleId =
                (int) ($_POST['article_id'] ?? 0);

            if ($articleId <= 0) {
                throw new Exception(
                    'Artikel tidak valid.'
                );
            }


            /*
             * Visitor ID.
             */

            $visitorId =
                $this->getVisitorId();


            /*
             * Hapus like.
             */

            $isUnliked =
                $this->articleLikeService->unlike(
                    $articleId,
                    $visitorId
                );


            /*
             * Kurangi counter hanya jika
             * sebelumnya memang sudah like.
             */

            if ($isUnliked) {

                $this->articleService->decrementLikeCount(
                    $articleId
                );
            }

        } catch (Exception $e) {

            /*
             * Nanti bisa diganti flash message.
             */
        }


        header(
            'Location: ' .
            ($_SERVER['HTTP_REFERER'] ?? '/article')
        );

        exit();
    }


    /*
    |--------------------------------------------------------------------------
    | VISITOR ID
    |--------------------------------------------------------------------------
    */

    private function getVisitorId(): string
    {
        if (!empty($_COOKIE['visitor_id'])) {
            return $_COOKIE['visitor_id'];
        }


        $visitorId =
            bin2hex(random_bytes(32));


        setcookie(
            'visitor_id',
            $visitorId,
            [
                'expires' =>
                    time() + (60 * 60 * 24 * 365),

                'path' => '/',
                'httponly' => true,
                'samesite' => 'Lax',
            ]
        );


        return $visitorId;
    }


    /*
    |--------------------------------------------------------------------------
    | ARTICLE BY TAG
    |--------------------------------------------------------------------------
    */

    public function tag(array $params): void
    {
        $tagSlug =
            trim($params['slug'] ?? '');


        $data = [
            'title' => 'Artikel',
            'current' => 'article',
            'tag' => null,
            'articles' => [],
        ];


        try {

            /*
             * Tag.
             */

            $tag =
                $this->tagService->getBySlug(
                    $tagSlug
                );


            if (!$tag) {
                throw new Exception(
                    'Tag not found.'
                );
            }


            $data['tag'] =
                $tag;


            /*
             * Article berdasarkan tag.
             */

            $data['articles'] =
                $this->articleService->getByTag(
                    $tag->slug
                );


        } catch (Exception $e) {

            $data['error'] =
                $e->getMessage();
        }


        /*
         * Render sesuai login.
         */

        if ($this->isLoggedIn()) {

            View::renderUser(
                '/Article/tag',
                $data
            );

            return;
        }


        View::renderPublic(
            '/Article/tag',
            $data
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ARTICLE TAG AJAX
    |--------------------------------------------------------------------------
    */

    public function getByTag(): void
    {
        header(
            'Content-Type: application/json'
        );


        $tagSlug =
            trim($_GET['slug'] ?? '');


        if ($tagSlug === '') {

            echo json_encode([
                'success' => false,
                'message' => 'Tag slug is required.'
            ]);

            return;
        }


        try {

            $tag =
                $this->tagService->getBySlug(
                    $tagSlug
                );


            if (!$tag) {

                echo json_encode([
                    'success' => false,
                    'message' => 'Tag not found.'
                ]);

                return;
            }


            $articles =
                $this->articleService->getByTag(
                    $tag->slug
                );


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


    /*
    |--------------------------------------------------------------------------
    | ADD ARTICLE PAGE
    |--------------------------------------------------------------------------
    */

    public function add(): void
    {
        View::renderUser(
            '/Article/add',
            [
                'title' => 'Add new Article'
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | POST ADD ARTICLE
    |--------------------------------------------------------------------------
    */

    public function postAdd(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }


        try {

            /*
            |--------------------------------------------------------------------------
            | Current User
            |--------------------------------------------------------------------------
            */

            $userId =
                $this->getCurrentUserId();


            if ($userId === null) {
                throw new Exception(
                    'User not logged in.'
                );
            }


            $user =
                $this->userService->getUserById(
                    $userId
                );


            /*
            |--------------------------------------------------------------------------
            | Article Request
            |--------------------------------------------------------------------------
            */

            $request =
                new ArticleAddRequest();


            $request->title =
                trim($_POST['title'] ?? '');


            $request->content =
                trim($_POST['content'] ?? '');


            /*
             * userId di request berarti
             * user yang sedang membuat artikel.
             */

            $request->userId =
                $user->id;


            /*
            |--------------------------------------------------------------------------
            | Create Article
            |--------------------------------------------------------------------------
            */

            $response =
                $this->articleService->add(
                    $request
                );


            $article =
                $response->article;


            /*
            |--------------------------------------------------------------------------
            | Creator
            |--------------------------------------------------------------------------
            */

            $this->articleUserService->add(
                $article->id,
                $user->id
            );


            /*
            |--------------------------------------------------------------------------
            | Selected Collaborators
            |--------------------------------------------------------------------------
            */

            $selectedUsers =
                $_POST['selectedUsers'] ?? [];


            if (is_array($selectedUsers)) {

                foreach ($selectedUsers as $selectedUserId) {

                    $selectedUserId =
                        (int) $selectedUserId;


                    /*
                     * Jangan tambahkan diri sendiri.
                     */

                    if (
                        $selectedUserId <= 0 ||
                        $selectedUserId === $user->id
                    ) {
                        continue;
                    }


                    $this->articleUserService->add(
                        $article->id,
                        $selectedUserId
                    );
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Tags
            |--------------------------------------------------------------------------
            */

            $selectedTags =
                $_POST['selectedTags'] ?? [];


            if (!is_array($selectedTags)) {
                $selectedTags = [];
            }


            $this->articleTagService->sync(
                $article->id,
                $selectedTags
            );


            /*
            |--------------------------------------------------------------------------
            | Images
            |--------------------------------------------------------------------------
            */

            $this->uploadImages(
                $article->id
            );


            /*
            |--------------------------------------------------------------------------
            | Success
            |--------------------------------------------------------------------------
            */

            $_SESSION['flash_message'] =
                'Success add new article';


            header(
                'Location: /me/article'
            );

            exit();

        } catch (Exception $e) {

            View::renderUser(
                '/Article/add',
                [
                    'title' => 'Add new Article',
                    'error' => $e->getMessage()
                ]
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT ARTICLE PAGE
    |--------------------------------------------------------------------------
    */

    public function edit(): void
    {
        $id =
            (int) ($_GET['id'] ?? 0);


        try {

            $userId =
                $this->getCurrentUserId();


            if ($userId === null) {
                throw new Exception(
                    'User not logged in.'
                );
            }


            $user =
                $this->userService->getUserById(
                    $userId
                );


            /*
             * Pastikan user terhubung dengan artikel.
             */

            $this->articleUserService
                ->validateUserCanEdit(
                    $id,
                    $user->id
                );


            /*
             * Article.
             */

            $article =
                $this->articleService->getById(
                    $id
                );


            /*
             * Owner.
             */

            $isOwner =
                (int) $article['owner_id'] ===
                (int) $user->id;


            /*
             * Images.
             */

            $images =
                $this->articleImageService
                    ->getByArticleId($id);


            /*
             * Collaborators.
             */

            $articleUsers =
                $this->articleUserService
                    ->getUsersByArticleId($id);


            /*
             * Tags.
             */

            $articleTags =
                $this->articleTagService
                    ->getByArticleId($id);


            View::renderUser(
                '/Article/edit',
                [
                    'title' => 'Edit Article',
                    'article' => $article,
                    'images' => $images,
                    'articleUsers' => $articleUsers,
                    'articleTags' => $articleTags,

                    'currentUserId' => $user->id,
                    'ownerId' => (int) $article['owner_id'],
                    'isOwner' => $isOwner
                ]
            );

        } catch (Exception $e) {

            View::renderUser(
                '/Article/edit',
                [
                    'title' => 'Edit Article',
                    'error' => $e->getMessage()
                ]
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | POST EDIT ARTICLE
    |--------------------------------------------------------------------------
    */

    public function postEdit(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }


        try {

            /*
            |--------------------------------------------------------------------------
            | Current User
            |--------------------------------------------------------------------------
            */

            $userId =
                $this->getCurrentUserId();


            if ($userId === null) {
                throw new Exception(
                    'User not logged in.'
                );
            }


            $user =
                $this->userService->getUserById(
                    $userId
                );


            /*
            |--------------------------------------------------------------------------
            | Article ID
            |--------------------------------------------------------------------------
            */

            $articleId =
                (int) ($_POST['id'] ?? 0);


            if ($articleId <= 0) {
                throw new Exception(
                    'Article not found.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Permission
            |--------------------------------------------------------------------------
            */

            $this->articleUserService
                ->validateUserCanEdit(
                    $articleId,
                    $user->id
                );


            /*
            |--------------------------------------------------------------------------
            | Article
            |--------------------------------------------------------------------------
            */

            $article =
                $this->articleService->getById(
                    $articleId
                );


            $isOwner =
                (int) $article['owner_id'] ===
                (int) $user->id;


            /*
            |--------------------------------------------------------------------------
            | Sync Collaborators
            |--------------------------------------------------------------------------
            |
            | Hanya owner yang boleh mengubah
            | collaborator.
            |
            */

            if ($isOwner) {

                $selectedUsers =
                    $_POST['selectedUsers'] ?? [];


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

            $request =
                new ArticleEditRequest();


            $request->id =
                $articleId;


            $request->title =
                trim($_POST['title'] ?? '');


            $request->content =
                trim($_POST['content'] ?? '');


            $this->articleService->edit(
                $request
            );


            /*
            |--------------------------------------------------------------------------
            | Sync Tags
            |--------------------------------------------------------------------------
            */

            $selectedTags =
                $_POST['selectedTags'] ?? [];


            if (!is_array($selectedTags)) {
                $selectedTags = [];
            }


            $this->articleTagService->sync(
                $articleId,
                $selectedTags
            );


            /*
            |--------------------------------------------------------------------------
            | Delete Images
            |--------------------------------------------------------------------------
            */

            $deleteImages =
                $_POST['deleteImages'] ?? [];


            if (is_array($deleteImages)) {

                foreach ($deleteImages as $imageId) {

                    $imageId =
                        (int) $imageId;


                    if ($imageId <= 0) {
                        continue;
                    }


                    $this->articleImageService
                        ->deleteById(
                            $imageId
                        );
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Existing Captions
            |--------------------------------------------------------------------------
            */

            $existingCaptions =
                $_POST['existingCaptions'] ?? [];


            if (is_array($existingCaptions)) {

                $deletedImageIds =
                    is_array($deleteImages)
                    ? array_map(
                        'intval',
                        $deleteImages
                    )
                    : [];


                foreach (
                    $existingCaptions
                    as $imageId => $caption
                ) {

                    $imageId =
                        (int) $imageId;


                    if ($imageId <= 0) {
                        continue;
                    }


                    /*
                     * Jangan update caption
                     * gambar yang sudah dihapus.
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
            | Upload New Images
            |--------------------------------------------------------------------------
            */

            $this->uploadImages(
                $articleId
            );


            /*
            |--------------------------------------------------------------------------
            | Success
            |--------------------------------------------------------------------------
            */

            $_SESSION['flash_message'] =
                'Success edit article';


            header(
                'Location: /me/article'
            );

            exit();

        } catch (Exception $e) {

            View::renderUser(
                '/Article/edit',
                [
                    'title' => 'Edit Article',
                    'error' => $e->getMessage()
                ]
            );
        }
    }

    public function userArticles(array $params): void
    {
        /*
         * Halaman artikel tidak boleh diakses admin.
         *
         * Route ini public, jadi pengecekan admin
         * tetap dilakukan di controller.
         */

        $user = $this->getCurrentUser();

        if (
            $user !== null &&
            $user->role === UserRole::ADMIN
        ) {
            header('Location: /');
            exit();
        }


        try {

            // Ambil username dari parameter route
            $username = ltrim(
                trim($params['username'] ?? ''),
                '@'
            );

            if ($username === '') {
                throw new Exception('Username cannot be empty.');
            }


            // Cari user berdasarkan username/email prefix
            $userProfile = $this->articleService->getByUsername($username);

            if (!$userProfile) {
                throw new Exception('User not found.');
            }


            // Ambil semua artikel yang terhubung
            // dengan user tersebut melalui article_user
            $articles = $this->articleService->getByUserId(
                $userProfile->id
            );


            View::renderUser('/Article/user', [
                'title' => "Article $username",
                'user' => $userProfile,
                'username' => $username,
                'articles' => $articles,
            ]);

        } catch (Exception $e) {

            echo $e->getMessage();
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

            /*
            |--------------------------------------------------------------------------
            | Current User
            |--------------------------------------------------------------------------
            */

            $userId =
                $this->getCurrentUserId();


            if ($userId === null) {
                throw new Exception(
                    'User not logged in.'
                );
            }


            $user =
                $this->userService->getUserById(
                    $userId
                );


            /*
            |--------------------------------------------------------------------------
            | Article ID
            |--------------------------------------------------------------------------
            */

            $articleId =
                (int) ($_POST['id'] ?? 0);


            if ($articleId <= 0) {
                throw new Exception(
                    'Article not found.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Article
            |--------------------------------------------------------------------------
            */

            $article =
                $this->articleService->getById(
                    $articleId
                );


            /*
            |--------------------------------------------------------------------------
            | Owner Check
            |--------------------------------------------------------------------------
            */

            if (
                (int) $article['owner_id'] !==
                (int) $user->id
            ) {

                throw new Exception(
                    'You are not allowed to delete this article.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Delete Images
            |--------------------------------------------------------------------------
            |
            | Harus dilakukan sebelum article dihapus.
            | Karena article_images memiliki ON DELETE CASCADE,
            | record image akan hilang ketika article dihapus.
            |
            */

            $images =
                $this->articleImageService
                    ->getByArticleId($articleId);


            foreach ($images as $image) {

                $imageId =
                    (int) $image['id'];

                if ($imageId <= 0) {
                    continue;
                }

                $this->articleImageService
                    ->deleteById(
                        $imageId
                    );
            }


            /*
            |--------------------------------------------------------------------------
            | Delete Article
            |--------------------------------------------------------------------------
            */

            $this->articleService
                ->deleteById(
                    $articleId
                );


            header(
                'Location: /article'
            );

            exit();

        } catch (Exception $e) {

            header(
                'Location: /article?error=' .
                urlencode($e->getMessage())
            );

            exit();
        }
    }


    /*
    |--------------------------------------------------------------------------
    | CURRENT USER ID
    |--------------------------------------------------------------------------
    */

    private function getCurrentUserId(): ?int
    {
        return $this->sessionService
            ->getCurrentUserId();
    }


    /*
    |--------------------------------------------------------------------------
    | CURRENT USER
    |--------------------------------------------------------------------------
    */

    private function getCurrentUser(): ?\app\Domain\User
    {
        $userId =
            $this->getCurrentUserId();


        if ($userId === null) {
            return null;
        }


        return $this->userService->getUserById(
            $userId
        );
    }


    /*
    |--------------------------------------------------------------------------
    | LOGIN STATUS
    |--------------------------------------------------------------------------
    */

    private function isLoggedIn(): bool
    {
        return $this->sessionService
            ->isLoggedIn();
    }


    /*
    |--------------------------------------------------------------------------
    | UPLOAD ARTICLE IMAGES
    |--------------------------------------------------------------------------
    */

    private function uploadImages(int $articleId): void
    {
        if (
            !isset($_FILES['images']['name']) ||
            !is_array($_FILES['images']['name'])
        ) {
            return;
        }


        $captions =
            $_POST['captions'] ?? [];


        if (!is_array($captions)) {
            $captions = [];
        }


        foreach (
            $_FILES['images']['name']
            as $key => $name
        ) {

            $error =
                $_FILES['images']['error'][$key]
                ?? UPLOAD_ERR_NO_FILE;


            /*
             * Tidak ada file.
             */

            if (
                $error ===
                UPLOAD_ERR_NO_FILE
            ) {
                continue;
            }


            /*
             * Struktur file sesuai $_FILES.
             */

            $file = [
                'name' =>
                    $_FILES['images']['name'][$key],

                'type' =>
                    $_FILES['images']['type'][$key],

                'tmp_name' =>
                    $_FILES['images']['tmp_name'][$key],

                'error' =>
                    $error,

                'size' =>
                    $_FILES['images']['size'][$key],
            ];


            /*
             * Caption berdasarkan index.
             */

            $caption =
                trim(
                    $captions[$key] ?? ''
                );


            /*
             * Simpan image.
             */

            $this->articleImageService->add(
                $articleId,
                $file,
                $caption
            );
        }
    }
}