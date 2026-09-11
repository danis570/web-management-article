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

    public function resetPasswordByAdmin(int $userId): void
    {
        if ($userId <= 0) {
            throw new Exception('User ID is invalid');
        }

        $user = $this->userRepository->findById($userId);

        if ($user === null) {
            throw new Exception('User not found');
        }

        $defaultPassword = 'pripnuippnudesaketambul';

        $hashedPassword = password_hash(
            $defaultPassword,
            PASSWORD_DEFAULT
        );

        if ($hashedPassword === false) {
            throw new Exception('Failed to hash password');
        }

        $this->userRepository->updatePassword(
            $userId,
            $hashedPassword
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CHANGE EMAIL
    |--------------------------------------------------------------------------
    */

    public function changeEmail(
        int $userId,
        string $email
    ): void {
        if ($userId <= 0) {
            throw new Exception(
                'User ID is invalid'
            );
        }

        $email = trim($email);

        if ($email === '') {
            throw new Exception(
                'Email cannot be blank'
            );
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception(
                'Email not valid'
            );
        }

        $emailExists = $this->userRepository
            ->existsByEmailExceptUser(
                $email,
                $userId
            );

        if ($emailExists) {
            throw new Exception(
                'Email already exists'
            );
        }

        $this->userRepository->updateEmail(
            $userId,
            $email
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CHANGE PASSWORD
    |--------------------------------------------------------------------------
    */

    public function changePassword(
        int $userId,
        string $currentPassword,
        string $newPassword
    ): void {
        if ($userId <= 0) {
            throw new Exception(
                'User ID is invalid'
            );
        }

        if ($currentPassword === '') {
            throw new Exception(
                'Current password cannot be blank'
            );
        }

        if ($newPassword === '') {
            throw new Exception(
                'New password cannot be blank'
            );
        }

        if (strlen($newPassword) < 8) {
            throw new Exception(
                'New password must be at least 8 characters'
            );
        }

        $user = $this->userRepository->findById(
            $userId
        );

        if ($user === null) {
            throw new Exception(
                'User not found'
            );
        }

        if (
            !password_verify(
                $currentPassword,
                $user->password
            )
        ) {
            throw new Exception(
                'Current password is wrong'
            );
        }

        if (
            password_verify(
                $newPassword,
                $user->password
            )
        ) {
            throw new Exception(
                'New password must be different from current password'
            );
        }

        $hashedPassword = password_hash(
            $newPassword,
            PASSWORD_DEFAULT
        );

        if ($hashedPassword === false) {
            throw new Exception(
                'Failed to hash password'
            );
        }

        $this->userRepository->updatePassword(
            $userId,
            $hashedPassword
        );
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
