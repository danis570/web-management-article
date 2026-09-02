<?php

namespace app\Service;

use app\Domain\User;
use app\Model\UserLoginRequest;
use app\Model\UserLoginResponse;
use app\Model\UserRegisterRequest;
use app\Model\UserRegisterResponse;
use app\Repository\UserRepository;
use Exception;

class UserService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(UserRegisterRequest $request): UserRegisterResponse
    {
        $this->registerValidation($request);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = password_hash($request->password, PASSWORD_BCRYPT);
        $this->userRepository->save($user);

        $response = new UserRegisterResponse();
        $response->user = $user;
        return $response;
    }

    private function registerValidation(UserRegisterRequest $request)
    {
        if (
            trim($request->name) == '' ||
            trim($request->email) == '' ||
            trim($request->password) == ''
        ) {
            throw new Exception('Name, email and password cannot blank');
        }

        if (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Email not valid');
        }

        $existingUser = $this->userRepository->findByEmail($request->email);
        if ($existingUser !== null) {
            throw new Exception('Email already exist');
        }
    }

    public function login(UserLoginRequest $request): UserLoginResponse
    {
        $this->loginValidation($request);

        $user = $this->userRepository->findByEmail($request->email);
        $userResponse = new UserLoginResponse();

        if ($user != null) {
            if (password_verify($request->password, $user->password)) {
                $userResponse->user = $user;
            } else {
                throw new Exception('Email or password is wrong');
            }
        }

        return $userResponse;
    }

    private function loginValidation(UserLoginRequest $request)
    {
        if (
            trim($request->email) == '' ||
            trim($request->password) == ''
        ) {
            throw new Exception('Name or password cannot blank');
        }

        if (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Email not valid');
        }
    }

    public function logout()
    {
        session_unset();
        session_destroy();
    }

    function getUserByEmail(string $email): array
    {
        $result = $this->userRepository->findByEmail($email);

        return $user = [
            'id' => $result->id,
            'name' => $result->name,
            'email' => $result->email,
            'password' => $result->password
        ] ?? throw new Exception('User not found');
    }
}