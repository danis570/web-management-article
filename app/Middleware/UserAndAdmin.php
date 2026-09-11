<?php

namespace app\Middleware;

use app\App\Database;
use app\Repository\SessionRepository;
use app\Service\SessionService;

class UserAndAdmin
{
    private SessionService $sessionService;

    public function __construct()
    {
        $pdo = Database::getConnection();

        $sessionRepository = new SessionRepository($pdo);

        $this->sessionService = new SessionService(
            $sessionRepository
        );
    }

    public function handle(): void
    {
        if (!$this->sessionService->isLoggedIn()) {
            header('Location: /login');
            exit();
        }
    }
}