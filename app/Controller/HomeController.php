<?php

namespace app\Controller;

use app\App\Database;
use app\App\View;
use app\Domain\UserRole;
use app\Repository\ProfileRepository;
use app\Repository\SessionRepository;
use app\Repository\UserRepository;
use app\Service\ProfileService;
use app\Service\SessionService;
use app\Service\UserService;

class HomeController
{
    private UserService $userService;
    private ProfileService $profileService;
    private SessionService $sessionService;

    public function __construct()
    {
        $pdo = Database::getConnection();

        $userRepository =
            new UserRepository($pdo);

        $this->userService = new UserService($userRepository);


        /*
        |--------------------------------------------------------------------------
        | Profile
        |--------------------------------------------------------------------------
        */

        $profileRepository =
            new ProfileRepository($pdo);

        $this->profileService =
            new ProfileService(
                $profileRepository
            );


        /*
        |--------------------------------------------------------------------------
        | Session
        |--------------------------------------------------------------------------
        */

        $sessionRepository =
            new SessionRepository($pdo);

        $this->sessionService =
            new SessionService(
                $sessionRepository
            );
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
            View::renderPublic('/home', [
                'title' => 'Blog App - by: Danish'
            ]);
            return;
        }

        $userId = $this->sessionService->getCurrentUserId();

        if ($userId === null) {
            View::renderPublic('/home', [
                'title' => 'Blog App - by: Danish'
            ]);
            return;
        }

        // 2. Ambil data user untuk mengecek Role
        $user = $this->userService->getUserById($userId);

        // 3. JIKA ADMIN: Langsung render halaman admin (Tanpa cek Profile)
        if ($user->role === UserRole::ADMIN) {
            View::renderAdmin('/dashboard', [
                'title' => 'Blog App - by: Danish',
                'user' => [
                    'name' => 'Administrator', // Nilai default/statis untuk admin
                    'position' => 'Super Admin',
                    'period' => '-',
                    'img' => 'default-admin.png'
                ]
            ]);
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | USER (Hanya diakses oleh user biasa yang memiliki profil)
        |--------------------------------------------------------------------------
        | Jalur di bawah ini hanya berjalan jika user BUKAN admin.
        */
        $profile = $this->profileService->getByUserId($user->id);

        $userData = [
            'name' => $profile->name,
            'position' => $profile->position,
            'period' => $profile->period,
            'img' => $profile->img
        ];

        View::renderUser('/dashboard', [
            'title' => 'Blog App - by: Danish',
            'user' => $userData
        ]);
    }

}
