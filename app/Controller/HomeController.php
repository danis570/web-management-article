<?php

namespace app\Controller;

use app\App\BaseController;
use app\App\Database;
use app\App\View;
use app\Domain\UserRole;
use app\Repository\ProfileRepository;
use app\Repository\SessionRepository;
use app\Repository\UserRepository;
use app\Service\ProfileService;
use app\Service\SessionService;
use app\Service\UserService;

class HomeController extends BaseController
{
    private UserService $userService;
    
    public function __construct()
    {
        $pdo = Database::getConnection();

        $userRepository =
            new UserRepository($pdo);

        $this->userService = new UserService($userRepository);


        $profileRepository = new ProfileRepository($pdo);
        $profileService = new ProfileService($profileRepository);

        $sessionRepository = new SessionRepository($pdo);
        $sessionService = new SessionService($sessionRepository);

        parent::__construct($sessionService, $profileService);
    }


    /*
    |--------------------------------------------------------------------------
    | HOME
    |--------------------------------------------------------------------------
    */

    public function home(): void
    {
        // 1. Cek status login
        if (!$this->sessionService->isLoggedIn()) {
            View::render('Public', '/Public/home', [
                'title' => 'Blog App - by: Danish'
            ]);
            return;
        }

        $userId = $this->sessionService->getCurrentUserId();

        if ($userId === null) {
            View::render('Public', '/Public/home', [
                'title' => 'Blog App - by: Danish'
            ]);
            return;
        }

        // 2. Ambil data user untuk mengecek Role
        $user = $this->userService->getUserById($userId);

        // 3. JIKA ADMIN: Langsung render halaman admin (Tanpa cek Profile)
        if ($user->role === UserRole::ADMIN) {
            View::render('Admin', '/Admin/dashboard', [
                'title' => 'Blog App - by: Danish',
                'user' => [
                    'name' => 'Administrator',
                    'position' => 'Super Admin',
                    'period' => '-',
                    'img' => 'default-admin.png'
                ]
            ]);
            return;
        }

        $profile = $this->profileService->getByUserId($user->id);

        $userData = [
            'name' => $profile->name,
            'position' => $profile->position,
            'period' => $profile->period,
            'img' => $profile->img
        ];

        View::render('User', '/User/dashboard', [
            'title' => 'Blog App - by: Danish',
            'user' => $userData
        ]);
    }

}
