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

        if (!$this->sessionService->isLoggedIn()) {

            View::renderPublic(
                '/home',
                [
                    'title' =>
                        'Blog App - by: Danish'
                ]
            );

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Current User ID
        |--------------------------------------------------------------------------
        */

        $userId =
            $this->sessionService->getCurrentUserId();


        if ($userId === null) {

            View::renderPublic(
                '/home',
                [
                    'title' =>
                        'Blog App - by: Danish'
                ]
            );

            return;
        }

        $user =
            $this->userService->getUserById(
                $userId
            );


        /*
        |--------------------------------------------------------------------------
        | Profile
        |--------------------------------------------------------------------------
        */

        $profile =
            $this->profileService->getByUserId(
                $user->id
            );


        /*
        |--------------------------------------------------------------------------
        | User Data
        |--------------------------------------------------------------------------
        */

        $userData = [
            'name' =>
                $profile->name,

            'position' =>
                $profile->position,

            'period' =>
                $profile->period,

            'img' =>
                $profile->img
        ];


        /*
        |--------------------------------------------------------------------------
        | ADMIN
        |--------------------------------------------------------------------------
        */

        if ($user->role === UserRole::ADMIN) {

            View::renderAdmin(
                '/dashboard',
                [
                    'title' =>
                        'Blog App - by: Danish',

                    'user' =>
                        $userData
                ]
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | USER
        |--------------------------------------------------------------------------
        */

        View::renderUser(
            '/dashboard',
            [
                'title' =>
                    'Blog App - by: Danish',

                'user' =>
                    $userData
            ]
        );
    }
}
