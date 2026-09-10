<?php

namespace app\Service;

use app\Domain\User;
use app\Model\UserLoginRequest;
use app\Model\UserLoginResponse;
use app\Repository\UserRepository;
use Exception;

class UserService
{
    private UserRepository $userRepository;

    public function __construct(
        UserRepository $userRepository
    ) {
        $this->userRepository = $userRepository;
    }

    public function login(
        UserLoginRequest $request
    ): UserLoginResponse {
        $this->loginValidation($request);

        $user = $this->userRepository->findByEmail(
            $request->email
        );

        if ($user === null) {
            throw new Exception(
                'Email or password is wrong'
            );
        }

        if (!password_verify(
            $request->password,
            $user->password
        )) {
            throw new Exception(
                'Email or password is wrong'
            );
        }

        $response = new UserLoginResponse();

        $response->user = $user;

        return $response;
    }

    private function loginValidation(
        UserLoginRequest $request
    ): void {
        if (
            trim($request->email) === '' ||
            trim($request->password) === ''
        ) {
            throw new Exception(
                'Email or password cannot be blank'
            );
        }

        if (!filter_var(
            $request->email,
            FILTER_VALIDATE_EMAIL
        )) {
            throw new Exception(
                'Email not valid'
            );
        }
    }

    public function logout(): void
    {
        session_unset();
        session_destroy();
    }

    public function getAll(): array
    {
        $result = $this->userRepository->getAll();

        if (empty($result)) {
            throw new Exception(
                'No users yet'
            );
        }

        return $result;
    }

    public function search(
        string $keyword,
        int $excludeUserId,
        int $limit = 10
    ): array {
        $keyword = trim($keyword);

        if ($keyword === '') {
            return [];
        }

        return $this->userRepository->search(
            $keyword,
            $excludeUserId,
            $limit
        );
    }

    public function getUserByEmail(
        string $email
    ): User {
        $result = $this->userRepository->findByEmail(
            $email
        );

        if ($result === null) {
            throw new Exception(
                'User not found'
            );
        }

        return $result;
    }
}