<?php

namespace app\Controller;

use app\App\Database;
use app\App\View;
use app\Repository\UserRepository;
use app\Service\UserService;
use Exception;

class UserController
{
    private UserService $userService;

    public function __construct()
    {
        $pdo = Database::getConnection();
        $userRepository = new UserRepository($pdo);
        $this->userService = new UserService($userRepository);
    }

    function users()
    {
        try {
            $user = $this->userService->getAll();
            View::renderAdmin('/User/users', [
                'title' => 'Users',
                'current' => 'users',
                'user' => $user
            ]);
        } catch (Exception $e) {
            $user = $this->userService->getAll();
            View::renderAdmin('/User/users', [
                'title' => 'Users',
                'current' => 'users',
                'user' => $user,
                'error' => $e->getMessage()
            ]);
        }
    }

    function search()
    {
        $keyword = $_GET['keyword'] ?? '';

        $user = $this->userService->getUserByEmail(
            $_SESSION['email']
        );

        $users = $this->userService->search(
            $keyword,
            $user->id
        );

        header('Content-Type: application/json');

        echo json_encode($users);
        exit();
    }


}