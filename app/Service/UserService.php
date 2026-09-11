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


    /*
    |--------------------------------------------------------------------------
    | LOGIN
    |--------------------------------------------------------------------------
    */

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

        if (
            !password_verify(
                $request->password,
                $user->password
            )
        ) {
            throw new Exception(
                'Email or password is wrong'
            );
        }

        $response = new UserLoginResponse();

        $response->user = $user;

        return $response;
    }


    /*
    |--------------------------------------------------------------------------
    | LOGIN VALIDATION
    |--------------------------------------------------------------------------
    */

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
    }


    /*
    |--------------------------------------------------------------------------
    | GET ALL USERS
    |--------------------------------------------------------------------------
    */

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


    /*
    |--------------------------------------------------------------------------
    | SEARCH USERS
    |--------------------------------------------------------------------------
    */

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


    /*
    |--------------------------------------------------------------------------
    | GET USER BY EMAIL
    |--------------------------------------------------------------------------
    */

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


    /*
    |--------------------------------------------------------------------------
    | GET USER BY ID
    |--------------------------------------------------------------------------
    */

    public function getUserById(
        int $id
    ): User {
        if ($id <= 0) {
            throw new Exception(
                'User ID is invalid'
            );
        }

        $result = $this->userRepository->findById(
            $id
        );

        if ($result === null) {
            throw new Exception(
                'User not found'
            );
        }

        return $result;
    }
}
