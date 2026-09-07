<?php

namespace app\Service;

use app\Domain\User;
use app\Domain\UserRole;
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

    public function register(UserRegisterRequest $request, array $imgFileInfo): UserRegisterResponse
    {
        $this->registerValidation($request);
        $this->registerUploadImgValidation($imgFileInfo['img_name'], $imgFileInfo['img_size']);
        $imgPath = '/uploads/user-img/' .
            $this->registerMoveUploadImg($imgFileInfo['img_name'], $imgFileInfo['img_temp_name'], $imgFileInfo['img_error']);

        $user = new User();
        $user->name = $request->name;
        $user->role = $request->role;
        $user->position = $request->position;
        $user->period = $request->period;
        $user->img = $imgPath;
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
            trim($request->position) == '' ||
            trim($request->period) == '' ||
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

    private function registerUploadImgValidation(string $imgName, string $imgSize)
    {
        $name = $imgName;
        $size = $imgSize;

        $validImgExtension = ['png', 'webp', 'jpg', 'jpeg', 'svg'];

        $imgExtension = explode('.', $name);
        $imgExtension = strtolower(end($imgExtension));

        if (!in_array($imgExtension, $validImgExtension)) {
            throw new Exception('Img format must png, webp, jpg, jpeg, svg');
        }

        if ((int) $size >= 5000) {
            throw new Exception('Max size: 5kb');
        }
    }

    private function registerMoveUploadImg(string $imgName, string $imgTempName, string $status): string
    {
        $path = __DIR__ . '/../../public/uploads/user-img/';
        $name = uniqid() . '-' . $imgName;
        $fullPath = $path . $name;
        if ((int) $status == 0) {
            move_uploaded_file($imgTempName, $fullPath);
            return $name;
        } else {
            throw new Exception('Error upload img');
        }
    }

    public function login(UserLoginRequest $request): UserLoginResponse
    {
        $this->loginValidation($request);
        $user = $this->userRepository->findByEmail($request->email);
        if ($user === null) {
            throw new Exception('Email or password is wrong');
        }

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

    public function getAll(): array
    {
        if ($this->userRepository->getAll()) {
            return $this->userRepository->getAll();
        } else {
            throw new Exception('Not users yet');
        }
    }

    function getUserByEmail(string $email): User
    {
        $result = $this->userRepository->findByEmail($email);

        if ($result === null) {
            throw new Exception('User not found');
        }

        return $result;
    }
}