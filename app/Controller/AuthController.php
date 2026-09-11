<?php

namespace app\Controller;

use app\App\Database;
use app\App\View;
use app\Domain\UserRole;
use app\Model\UserProfileRegisterRequest;
use app\Model\UserLoginRequest;
use app\Model\UserRegisterRequest;
use app\Repository\ProfileRepository;
use app\Repository\SessionRepository;
use app\Repository\UserRepository;
use app\Service\ProfileService;
use app\Service\RegistrationService;
use app\Service\SessionService;
use app\Service\UserService;
use Exception;

class AuthController
{
    private UserRepository $userRepository;
    private ProfileRepository $profileRepository;

    private UserService $userService;
    private ProfileService $profileService;
    private RegistrationService $registrationService;

    private SessionRepository $sessionRepository;
    private SessionService $sessionService;

    public function __construct()
    {
        $pdo = Database::getConnection();

        $this->userRepository = new UserRepository($pdo);

        $this->profileRepository = new ProfileRepository($pdo);

        $this->profileService = new ProfileService(
            $this->profileRepository
        );

        $this->userService = new UserService(
            $this->userRepository
        );

        $this->sessionRepository = new SessionRepository($pdo);

        $this->sessionService = new SessionService(
            $this->sessionRepository
        );

        $this->registrationService = new RegistrationService(
            $pdo,
            $this->userRepository,
            $this->profileService
        );
    }

    public function login()
    {
        View::renderPublic('/Auth/login', [
            'current' => 'login',
            'title' => 'Login'
        ]);
    }

    public function postLogin()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        $request = new UserLoginRequest();

        $request->email = $_POST['email'] ?? '';
        $request->password = $_POST['password'] ?? '';

        try {
            $user = $this->userService->login($request);

            // Buat session di database
            $session = $this->sessionService->create(
                $user->user->id
            );

            // Simpan ID session ke cookie browser
            $this->sessionService->setCookie(
                $session->id
            );

            // Ambil URL tujuan setelah login
            $redirect = $_POST['redirect'] ?? '/';

            if (
                !is_string($redirect) ||
                $redirect === '' ||
                $redirect[0] !== '/' ||
                str_starts_with($redirect, '//')
            ) {
                $redirect = '/';
            }

            header('Location: ' . $redirect);
            exit();

        } catch (Exception $e) {
            View::renderPublic('/Auth/login', [
                'title' => 'Login',
                'current' => 'login',
                'error' => $e->getMessage()
            ]);
        }
    }


    public function register()
    {
        View::renderAdmin('/Auth/register', [
            'title' => 'Register'
        ]);
    }

    public function postRegister()
    {
        $request = new UserRegisterRequest();

        // =========================
        // ACCOUNT
        // =========================

        $request->role = UserRole::from(
            $_POST['role'] ?? 'user'
        );

        $request->email = $_POST['email'] ?? '';

        $request->password = $_POST['password'] ?? '';

        // =========================
        // PROFILE
        // =========================

        $profile = new UserProfileRegisterRequest();

        $profile->name = $_POST['name'] ?? '';

        $profile->position = $_POST['position'] ?? '';

        $profile->period = $_POST['period'] ?? '';

        $request->profile = $profile;

        // =========================
        // IMAGE
        // =========================

        $imgFileInfo = null;

        if (
            isset($_FILES['img']) &&
            $_FILES['img']['error'] !== UPLOAD_ERR_NO_FILE
        ) {
            $imgFileInfo = [
                'name' => $_FILES['img']['name'],
                'size' => $_FILES['img']['size'],
                'error' => $_FILES['img']['error'],
                'tmp_name' => $_FILES['img']['tmp_name']
            ];
        }

        try {
            $this->registrationService->register(
                $request,
                $imgFileInfo
            );

            $_SESSION['flash_message'] =
                'Success add new user';

            header('Location: /users');
            exit();

        } catch (Exception $e) {
            View::renderAdmin('/Auth/register', [
                'title' => 'Register',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function logout()
    {
        $this->sessionService->logout();

        header('Location: /');
        exit();
    }
}