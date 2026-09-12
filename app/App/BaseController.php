<?php

namespace app\App;

use app\App\View;
use app\Service\SessionService;
use app\Service\ProfileService;

abstract class BaseController
{
    protected SessionService $sessionService;
    protected ProfileService $profileService;

    public function __construct(SessionService $sessionService, ProfileService $profileService)
    {
        $this->sessionService = $sessionService;
        $this->profileService = $profileService;

        $userId = $this->sessionService->getCurrentUserId();

        if ($userId !== null) {
            
            $profileImg = 'default.png';
            $profileName = 'Administrator';

            try {
                $profile = $this->profileService->getByUserId($userId);

                if ($profile) {
                    $profileImg = $profile->img ?? 'default.png';
                    $profileName = $profile->name ?? 'Anggota';
                }
            } catch (\Exception $e) {
                
            }

            View::share('authProfile', [
                'img' => $profileImg,
                'name' => $profileName
            ]);
        }
    }

}
