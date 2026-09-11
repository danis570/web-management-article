<?php

namespace app\Controller;

use app\App\Database;
use app\App\View;
use app\Repository\ProfileRepository;
use app\Repository\SessionRepository;
use app\Service\ProfileService;
use app\Service\SessionService;
use Exception;

class ProfileController
{
    private ProfileService $profileService;
    private SessionService $sessionService;

    public function __construct()
    {
        $pdo = Database::getConnection();

        $profileRepository = new ProfileRepository($pdo);
        $sessionRepository = new SessionRepository($pdo);

        $this->profileService = new ProfileService(
            $profileRepository
        );

        $this->sessionService = new SessionService(
            $sessionRepository
        );
    }

    public function profile(): void
    {
        try {
            $userId = $this->sessionService->getCurrentUserId();

            if ($userId === null) {
                header('Location: /login');
                exit();
            }

            $profile = $this->profileService->getByUserId(
                $userId
            );

            View::renderUser('/Profile/profile', [
                'title' => 'Profile',
                'profile' => $profile
            ]);
        } catch (Exception $e) {
            View::renderUser('/Profile/profile', [
                'title' => 'Profile',
                'profile' => null,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function postUpdate(): void
    {
        try {
            $userId = $this->sessionService->getCurrentUserId();

            if ($userId === null) {
                header('Location: /login');
                exit();
            }

            $profile = $this->profileService->getByUserId(
                $userId
            );

            $profile->name = $_POST['name'] ?? '';
            $profile->position = $_POST['position'] ?? '';
            $profile->period = $_POST['period'] ?? '';

            $imgFileInfo = null;

            if (
                isset($_FILES['img']) &&
                $_FILES['img']['error'] !== UPLOAD_ERR_NO_FILE
            ) {
                $imgFileInfo = $_FILES['img'];
            }

            $this->profileService->update(
                $profile,
                $imgFileInfo
            );

            $_SESSION['flash_message'] = 'Secces update your Profile';

            header('Location: /profile');
            exit();
        } catch (Exception $e) {
            View::renderUser('/Profile/profile', [
                'title' => 'Profile',
                'profile' => $profile ?? null,
                'error' => $e->getMessage()
            ]);
        }
    }
}