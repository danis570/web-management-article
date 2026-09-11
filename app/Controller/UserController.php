<?php

namespace app\Controller;

use app\App\Database;
use app\App\View;
use app\Repository\SessionRepository;
use app\Repository\UserRepository;
use app\Service\SessionService;
use app\Service\UserService;
use Exception;

class UserController
{
    private UserService $userService;
    private SessionService $sessionService;

    public function __construct()
    {
        $pdo = Database::getConnection();

        $userRepository = new UserRepository($pdo);
        $sessionRepository = new SessionRepository($pdo);

        $this->userService = new UserService(
            $userRepository
        );

        $this->sessionService = new SessionService(
            $sessionRepository
        );
    }

    public function users(): void
    {
        try {

            $users = $this->userService->getAll();

            View::renderAdmin('/User/users', [
                'title' => 'Users',
                'current' => 'users',
                'user' => $users
            ]);

        } catch (Exception $e) {

            View::renderAdmin('/User/users', [
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
}
