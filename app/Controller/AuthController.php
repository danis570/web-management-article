<?php

namespace app\Controller;

use app\App\Database;
use app\App\View;
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
                header('Location: /');
            } catch (Exception $e) {
                View::renderPublic('/Auth/login', [
                    'title' => 'Login',
                    'error' => $e->getMessage()
                ]);
            }
        }
    }
    function register()
    {
        View::renderPublic('/Auth/register', [
            'title' => 'Register'
        ]);
    }

    function postRegister()
    {
        $request = new UserRegisterRequest();
        $request->name = $_POST['name'];
        $request->email = $_POST['email'];
        $request->password = $_POST['password'];

        try {
            $this->userService->register($request);
            header('Location: /login');
        } catch (Exception $e) {
            View::renderPublic('/Auth/register', [
                'title' => 'Register',
                'error' => $e->getMessage()
            ]);
        }
    }

    function logout()
    {
        $this->userService->logout();
        header('Location: /');
    }
}