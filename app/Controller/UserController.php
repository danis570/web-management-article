<?php

namespace app\Controller;

use app\App\BaseController;
use app\App\Database;
use app\App\View;
use app\Model\UserChangeEmailRequest;
use app\Model\UserChangePasswordRequest;
use app\Repository\ArticleRepository;
use app\Repository\GalleryImageRepository;
use app\Repository\GalleryRepository;
use app\Repository\ProfileRepository;
use app\Repository\SessionRepository;
use app\Repository\UserRepository;
use app\Service\ArticleService;
use app\Service\GalleryImageService;
use app\Service\GalleryService;
use app\Service\ProfileService;
use app\Service\SessionService;
use app\Service\UserService;
use Exception;

class UserController extends BaseController
{
    private UserService $userService;
    private ArticleService $articleService;
    private GalleryService $galleryService;
    private GalleryImageService $galleryImageService;



    public function __construct()
    {
        $pdo = Database::getConnection();

        $userRepository = new UserRepository($pdo);
        $sessionRepository = new SessionRepository($pdo);
        $articleRepository = new ArticleRepository($pdo);
        $galleryRepository = new GalleryRepository($pdo);

        $this->userService = new UserService(
            $userRepository
        );
        $galleryImageRepository = new GalleryImageRepository($pdo);
        $this->galleryImageService = new GalleryImageService($galleryImageRepository);

        $this->articleService = new ArticleService($articleRepository, $userRepository);
        $this->galleryService = new GalleryService($galleryRepository);

        $sessionService = new SessionService($sessionRepository);
        $profileRepository = new ProfileRepository($pdo);
        $profileService = new ProfileService($profileRepository);

        parent::__construct($sessionService, $profileService);
    }

    public function index(): void
    {
        try {

            $users = $this->userService->getAll();

            $profiles = [];

            foreach ($users as $user) {

                $profile = $this->profileService
                    ->getByUserId((int) $user['id']);

                if ($profile) {
                    $profiles[$user['id']] = $profile;
                }
            }

            View::render('Public', '/Users/users', [
                'title' => 'Users',
                'users' => $users,
                'profiles' => $profiles,
            ]);

        } catch (Exception $e) {

            http_response_code(404);

            echo $e->getMessage();
        }
    }

    public function users(): void
    {
        try {

            $users = $this->userService->getAll();

            View::render('Admin', '/Admin/User/users', [
                'title' => 'Users',
                'current' => 'users',
                'user' => $users
            ]);

        } catch (Exception $e) {

            View::render('Admin', '/Admin/User/users', [
                'title' => 'Users',
                'current' => 'users',
                'user' => [],
                'error' => $e->getMessage()
            ]);
        }
    }

    public function search(): void
    {
        $keyword = trim($_GET['keyword'] ?? '');

        $userId = $this->sessionService->getCurrentUserId();

        if ($userId === null) {
            header('Content-Type: application/json');

            http_response_code(401);

            echo json_encode([
                'error' => 'Unauthorized'
            ]);

            exit();
        }

        $users = $this->userService->search(
            $keyword,
            $userId
        );

        header('Content-Type: application/json');

        echo json_encode($users);

        exit();
    }

    public function account(): void
    {
        try {

            $userId = $this->sessionService->getCurrentUserId();

            if ($userId === null) {
                header('Location: /login');
                exit();
            }

            $user = $this->userService->getUserById($userId);

            View::render('User', '/User/Profile/account', [
                'title' => 'Account',
                'user' => $user
            ]);

        } catch (Exception $e) {

            View::render('User', '/User/Profile/account', [
                'title' => 'Account',
                'user' => null,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function postChangeEmail(): void
    {
        try {

            $userId = $this->sessionService->getCurrentUserId();

            if ($userId === null) {
                header('Location: /login');
                exit();
            }

            $request = new UserChangeEmailRequest();

            $request->userId = $userId;
            $request->email = $_POST['email'] ?? '';

            $this->userService->changeEmail(
                $request->userId,
                $request->email
            );

            $_SESSION['flash_message'] = 'Email updated successfully.';

            header('Location: /account');
            exit();

        } catch (Exception $e) {

            View::render('User', '/User/Profile/account', [
                'title' => 'Account',
                'user' => isset($userId)
                    ? $this->userService->getUserById($userId)
                    : null,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function postChangePassword(): void
    {
        try {

            $userId = $this->sessionService->getCurrentUserId();

            if ($userId === null) {
                header('Location: /login');
                exit();
            }

            $request = new UserChangePasswordRequest();

            $request->userId = $userId;
            $request->currentPassword = $_POST['current_password'] ?? '';
            $request->newPassword = $_POST['new_password'] ?? '';

            $this->userService->changePassword(
                $request->userId,
                $request->currentPassword,
                $request->newPassword
            );

            // Password berhasil diubah.
            // Hapus semua session user dari semua perangkat.
            $this->sessionService->revokeAllByUserId($userId);

            // Karena session sudah dihapus,
            // flash message sebaiknya jangan memakai $_SESSION lagi.
            header('Location: /login');
            exit();

        } catch (Exception $e) {

            View::render('User', '/User/Profile/account', [
                'title' => 'Account',
                'user' => isset($userId)
                    ? $this->userService->getUserById($userId)
                    : null,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function postResetPassword(): void
    {
        try {
            // 1. Ambil dan validasi ID user target dari data _POST
            $targetUserId = isset($_POST['user_id']) ? (int) $_POST['user_id'] : 0;
            if ($targetUserId <= 0) {
                throw new Exception("User ID is invalid.");
            }

            // 2. Ambil dan validasi ID admin yang sedang login
            $adminUserId = $this->sessionService->getCurrentUserId();
            if ($adminUserId === null) {
                throw new Exception("Unauthorized.");
            }

            // 3. Eksekusi reset password untuk user target
            $this->userService->resetPasswordByAdmin($targetUserId);

            // 4. Hapus session milik user target jika bukan admin itu sendiri
            if ($targetUserId !== $adminUserId) {
                $this->sessionService->revokeAllByUserId($targetUserId);
            }

            // 5. Set pesan sukses dan redirect
            $_SESSION['flash_message'] = "Password user berhasil di-reset.";
            header("Location: /users");
            exit;

        } catch (Exception $e) {
            // 6. Handling error: Set pesan error dan redirect
            $_SESSION['flash_message'] = $e->getMessage();
            header("Location: /users");
            exit;
        }
    }




    public function profile(array $params): void
    {
        try {

            $username = ltrim(
                trim($params['username'] ?? ''),
                '@'
            );

            if ($username === '') {
                throw new Exception('Username cannot be empty.');
            }

            /*
             * ==========================
             * User
             * ==========================
             */

            $user = $this->userService
                ->getByUsername($username);

            if (!$user) {
                throw new Exception('User not found.');
            }

            /*
             * ==========================
             * Profile
             * ==========================
             */

            $profile = $this->profileService
                ->getByUserId($user->id);

            if (!$profile) {
                throw new Exception('Profile not found.');
            }

            /*
             * ==========================
             * Latest Posts
             * ==========================
             */

            // Artikel
            try {

                $articles = $this->articleService
                    ->getByUserId($user->id);

            } catch (Exception $e) {

                $articles = [];
            }

            // Gallery
            try {

                $galleries = $this->galleryService
                    ->getByUserId($user->id);

            } catch (Exception $e) {

                $galleries = [];
            }

            /*
             * ==========================
             * Gallery Preview Images
             * ==========================
             */

            $galleryImages = [];

            foreach ($galleries as $gallery) {

                $previewImage = $this->galleryImageService
                    ->getFirstByGalleryId($gallery->id);

                if ($previewImage) {

                    $galleryImages[$gallery->id] = $previewImage;
                }
            }

            /*
             * ==========================
             * Gabungkan Article + Gallery
             * ==========================
             */

            $latestPosts = [];

            foreach ($articles as $article) {

                $latestPosts[] = [
                    'type' => 'article',
                    'data' => $article,
                    'date' => $article['created_at'],
                ];
            }

            foreach ($galleries as $gallery) {

                $latestPosts[] = [
                    'type' => 'gallery',
                    'data' => $gallery,
                    'date' => $gallery->createdAt,
                ];
            }

            /*
             * ==========================
             * Urutkan dari terbaru
             * ==========================
             */

            usort(
                $latestPosts,
                function ($a, $b) {
                    return strtotime($b['date'])
                        <=> strtotime($a['date']);
                }
            );

            /*
             * ==========================
             * Render
             * ==========================
             */

            View::render('Public', '/Users/detail', [
                'title' => $profile->name,
                'user' => $user,
                'profile' => $profile,
                'latestPosts' => $latestPosts,
                'gallery_images' => $galleryImages,
            ]);

        } catch (Exception $e) {

            http_response_code(404);

            echo $e->getMessage();
        }
    }



}
