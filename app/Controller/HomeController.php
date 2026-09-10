<?php

namespace app\Controller;

use app\App\Database;
use app\App\View;
use app\Domain\UserRole;
use app\Repository\ProfileRepository;
use app\Repository\UserRepository;
use app\Service\ProfileService;
use app\Service\UserService;

class HomeController
{
    private UserService $userService;
    private ProfileService $profileService;

    public function __construct()
    {
        $pdo = Database::getConnection();

        $userRepository = new UserRepository($pdo);

        $profileRepository = new ProfileRepository($pdo);

        $this->userService = new UserService(
            $userRepository,
            new ProfileService($profileRepository)
        );

        $this->profileService = new ProfileService(
            $profileRepository
        );
    }

    public function home()
    {
        if (($_SESSION['login'] ?? false) !== true) {
            View::renderPublic('/home', [
                'title' => 'Blog App - by: Danish'
            ]);

            return;
        }

        $user = $this->userService->getUserByEmail(
            $_SESSION['email'] ?? ''
        );

        $profile = $this->profileService->getByUserId(
            $user->id
        );

        $userData = [
            'name' => $profile->name,
            'position' => $profile->position,
            'period' => $profile->period,
            'img' => $profile->img
        ];

        if ($user->role === UserRole::ADMIN) {
            View::renderAdmin('/dashboard', [
                'title' => 'Blog App - by: Danish',
                'user' => $userData
            ]);

            return;
        }

        View::renderUser('/dashboard', [
            'title' => 'Blog App - by: Danish',
            'user' => $userData
        ]);
    }
}