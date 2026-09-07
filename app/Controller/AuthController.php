<?php

namespace app\Controller;

use app\App\Database;
use app\App\View;
use app\Domain\UserRole;
use app\Model\UserLoginRequest;
use app\Model\UserRegisterRequest;
use app\Repository\UserRepository;
use app\Service\UserService;
use Exception;

class AuthController
{
    private UserRepository $userRepository;
    private UserService $userService;

    public function __construct()
    {
        $this->userRepository = new UserRepository(Database::getConnection());
        $this->userService = new UserService($this->userRepository);
    }

    function login()
    {
        View::renderPublic('/Auth/login', [
            'current' => 'login',
            'title' => 'Login'
        ]);
    }

    function postLogin()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $request = new UserLoginRequest();
            $request->email = $_POST['email'];
            $request->password = $_POST['password'];
            try {
                $user = $this->userService->login($request);
                $_SESSION['login'] = true;
                $_SESSION['email'] = $user->user->email;
                if ($user->user->role === UserRole::ADMIN) {
                    $_SESSION['admin'] = true;
                }
                header('Location: /');
                exit();
            } catch (Exception $e) {
                View::renderPublic('/Auth/login', [
                    'title' => 'Login',
                    'current' => 'login',
                    'error' => $e->getMessage()
                ]);
            }
        }
    }
    function register()
    {
        View::renderAdmin('/Auth/register', [
            'title' => 'Register'
        ]);
    }

    function postRegister()
    {
        $request = new UserRegisterRequest();
        $request->name = $_POST['name'];
        $request->position = $_POST['position'];
        $request->role = UserRole::from($_POST['role']);
        $request->img = $_FILES['img']['name'] ?? null;
        $request->period = $_POST['period'];
        $request->email = $_POST['email'];
        $request->password = $_POST['password'];

        $imgFileInfo = [
            'img_name' => $_FILES['img']['name'],
            'img_size' => $_FILES['img']['size'],
            'img_error' => $_FILES['img']['error'],
            'img_temp_name' => $_FILES['img']['tmp_name']
        ];

        try {
            $this->userService->register($request, $imgFileInfo);
            header('Location: /users');
            exit();
        } catch (Exception $e) {
            View::renderAdmin('/Auth/register', [
                'title' => 'Register',
                'error' => $e->getMessage()
            ]);
        }
    }

    function logout()
    {
        $this->userService->logout();
        header('Location: /');
        exit();
    }
}