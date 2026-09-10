<?php

namespace app\Service;

use app\Domain\User;
use app\Model\UserRegisterRequest;
use app\Model\UserRegisterResponse;
use app\Repository\UserRepository;
use PDO;
use Exception;
use Throwable;

class RegistrationService
{
    private PDO $pdo;
    private UserRepository $userRepository;
    private ProfileService $profileService;

    public function __construct(
        PDO $pdo,
        UserRepository $userRepository,
        ProfileService $profileService
    ) {
        $this->pdo = $pdo;
        $this->userRepository = $userRepository;
        $this->profileService = $profileService;
    }

    public function register(
        UserRegisterRequest $request,
        ?array $imgFileInfo = null
    ): UserRegisterResponse {
        $this->validation($request);

        $this->pdo->beginTransaction();

        try {

            // =========================
            // ACCOUNT
            // =========================

            $user = new User();

            $user->role = $request->role;

            $user->email = $request->email;

            $user->password = password_hash(
                $request->password,
                PASSWORD_BCRYPT
            );

            $this->userRepository->save($user);


            // =========================
            // PROFILE
            // =========================

            $profile = $this->profileService->create(
                $request->profile,
                $user->id,
                $imgFileInfo
            );


            // =========================
            // COMMIT
            // =========================

            $this->pdo->commit();

            $response = new UserRegisterResponse();

            $response->user = $user;

            return $response;

        } catch (Throwable $e) {

            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }

            throw $e;
        }
    }

    private function validation(
        UserRegisterRequest $request
    ): void {
        if (
            trim($request->email) === '' ||
            trim($request->password) === ''
        ) {
            throw new Exception(
                'Email and password cannot be blank'
            );
        }

        if (
            !filter_var(
                $request->email,
                FILTER_VALIDATE_EMAIL
            )
        ) {
            throw new Exception(
                'Email not valid'
            );
        }

        $existingUser = $this->userRepository->findByEmail(
            $request->email
        );

        if ($existingUser !== null) {
            throw new Exception(
                'Email already exist'
            );
        }
    }
}