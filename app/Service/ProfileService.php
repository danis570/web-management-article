<?php

namespace app\Service;

use app\Domain\Profile;
use app\Model\UserProfileRegisterRequest;
use app\Repository\ProfileRepository;
use Exception;

class ProfileService
{
    private ProfileRepository $profileRepository;

    public function __construct(ProfileRepository $profileRepository)
    {
        $this->profileRepository = $profileRepository;
    }

    public function create(
        UserProfileRegisterRequest $request,
        int $userId,
        ?array $imgFileInfo = null
    ): Profile {
        $this->validation($request);

        $profile = new Profile();

        $profile->userId = $userId;
        $profile->name = $request->name;
        $profile->position = $request->position;
        $profile->period = $request->period;
        $profile->img = null;

        if (
            $imgFileInfo !== null &&
            $imgFileInfo['error'] !== UPLOAD_ERR_NO_FILE
        ) {
            $this->uploadImgValidation(
                $imgFileInfo['name'],
                $imgFileInfo['size']
            );

            $profile->img = '/uploads/user-img/' .
                $this->moveUploadImg(
                    $imgFileInfo['name'],
                    $imgFileInfo['tmp_name'],
                    $imgFileInfo['error']
                );
        }

        return $this->profileRepository->save($profile);
    }

    public function getByUserId(int $userId): Profile
    {
        $profile = $this->profileRepository->findByUserId($userId);

        if ($profile === null) {
            throw new Exception('Profile not found');
        }

        return $profile;
    }

    public function update(
        Profile $profile,
        ?array $imgFileInfo = null
    ): Profile {
        $this->validationProfile($profile);

        if (
            $imgFileInfo !== null &&
            $imgFileInfo['error'] !== UPLOAD_ERR_NO_FILE
        ) {
            $this->uploadImgValidation(
                $imgFileInfo['name'],
                $imgFileInfo['size']
            );

            $profile->img = '/uploads/user-img/' .
                $this->moveUploadImg(
                    $imgFileInfo['name'],
                    $imgFileInfo['tmp_name'],
                    $imgFileInfo['error']
                );
        }

        return $this->profileRepository->update($profile);
    }


    public function deleteByUserId(int $userId): void
    {
        $this->profileRepository->deleteByUserId($userId);
    }

    private function validation(
        UserProfileRegisterRequest $request
    ): void {
        if (
            trim($request->name) === '' ||
            trim($request->position) === '' ||
            trim($request->period) === ''
        ) {
            throw new Exception(
                'Name, position and period cannot be blank'
            );
        }
    }

    private function validationProfile(Profile $profile): void
    {
        if (
            trim($profile->name) === '' ||
            trim($profile->position) === '' ||
            trim($profile->period) === ''
        ) {
            throw new Exception(
                'Name, position and period cannot be blank'
            );
        }
    }

    private function uploadImgValidation(
        string $imgName,
        int|string $imgSize
    ): void {
        $validImgExtension = [
            'png',
            'webp',
            'jpg',
            'jpeg',
            'svg'
        ];

        $imgExtension = strtolower(
            pathinfo($imgName, PATHINFO_EXTENSION)
        );

        if (!in_array($imgExtension, $validImgExtension, true)) {
            throw new Exception(
                'Img format must png, webp, jpg, jpeg, svg'
            );
        }

        if ((int) $imgSize > 100000) {
            throw new Exception('Max size: 100kb');
        }
    }

    private function moveUploadImg(
        string $imgName,
        string $imgTempName,
        int|string $status
    ): string {
        $path = __DIR__ . '/../../public/uploads/user-img/';

        $name = uniqid() . '-' . $imgName;

        $fullPath = $path . $name;

        if ((int) $status !== UPLOAD_ERR_OK) {
            throw new Exception('Error upload img');
        }

        if (!move_uploaded_file($imgTempName, $fullPath)) {
            throw new Exception(
                'Failed to move uploaded image'
            );
        }

        return $name;
    }
}