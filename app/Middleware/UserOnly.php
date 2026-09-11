<?php

namespace app\Middleware;

use app\App\Database;
use app\Domain\UserRole;
use app\Repository\SessionRepository;
use app\Repository\UserRepository;
use app\Service\SessionService;
use app\Service\UserService;

class UserOnly
{
    private SessionService $sessionService;
    private UserService $userService;

    public function __construct()
    {
        $pdo = Database::getConnection();

        $sessionRepository = new SessionRepository($pdo);
        $userRepository = new UserRepository($pdo);

        $this->sessionService = new SessionService(
            $sessionRepository
        );

        $this->userService = new UserService(
            $userRepository
        );
    }

    public function handle(): void
    {
        if (!$this->sessionService->isLoggedIn()) {
            header('Location: /login');
            exit();
        }

        $userId = $this->sessionService->getCurrentUserId();

        if ($userId === null) {
            header('Location: /login');
            exit();
        }

        $user = $this->userService->getUserById($userId);

        if ($user->role === UserRole::ADMIN) {
            header('Location: /');
            exit();
        }
    }
}