<?php

namespace app\Controller;

use app\App\Database;
use app\App\View;
use app\Repository\UserRepository;
use app\Service\UserService;

class HomeController
{
    private UserService $userService;

    public function __construct()
    {
        $userRepository = new UserRepository(Database::getConnection());
        $this->userService = new UserService($userRepository);
    }

    function home()
    {
        if (($_SESSION['login'] ?? false) != true) {
            View::renderPublic('/home', [
                'title' => 'Blog App - by: Danish'
            ]);
            return;
        }

        $user = $this->userService->getUserByEmail($_SESSION['email']);

        View::renderUser('/dashboard', [
            'title' => 'Blog App - by: Danish',
            'user' => [
                'name' => $user['name']
            ]
        ]);
    }
}