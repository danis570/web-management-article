<?php

use app\App\AutoLoader;
use app\App\Database;
use app\Domain\User;
use app\Model\UserLoginRequest;
use app\Model\UserLoginResponse;
use app\Model\UserRegisterRequest;
use app\Model\UserRegisterResponse;
use app\Repository\UserRepository;
use app\Service\UserService;

require_once __DIR__ . '/../../app/App/AutoLoader.php';
AutoLoader::loadClass();

function testRegisterSuccess()
{
    $userRepository = new UserRepository(Database::getConnection());
    $userService = new UserService($userRepository);

    $userRepository->deleteAll();

    $user = new UserRegisterRequest();
    $user->name = 'Danish';
    $user->email = 'danish@gmail.com';
    $user->password = 'password';

    $result = $userService->register($user);
    if ($result instanceof UserRegisterResponse) {
        echo 'Test register user success: success';
    }
}

function testRegisterfailedDuplicateEmail()
{
    $userRepository = new UserRepository(Database::getConnection());
    $userService = new UserService($userRepository);

    $userRepository->deleteAll();

    $user = new UserRegisterRequest();
    $user->name = 'Danish';
    $user->email = 'danish@gmail.com';
    $user->password = 'password';
    $userService->register($user);

    $user2 = new UserRegisterRequest();
    $user2->name = 'Danish';
    $user2->email = 'danish@gmail.com';
    $user2->password = 'password';

    try {
        $userService->register($user2);
        echo 'Test gagal: function meloloskan duplikat email';
    } catch (Exception $e) {
        if (assert($e->getMessage() == 'Email already exist', 'Pesan error berbeda')) {
            echo 'test sukses';
        }
    }

}

function testRegisterfailedFieldEmpty()
{
    $userRepository = new UserRepository(Database::getConnection());
    $userService = new UserService($userRepository);

    $userRepository->deleteAll();

    $user = new UserRegisterRequest();
    $user->name = '';
    $user->email = 'danish@gmail.com';
    $user->password = 'password';


    try {
        $userService->register($user);
        echo 'Test gagal: function meloloskan field kosong';
    } catch (Exception $e) {
        if (assert($e->getMessage() == 'Name, email and password cannot blank', 'Pesan error berbeda')) {
            echo 'test sukses';
        }
    }

}

function testUserLogin()
{
    $userRepository = new UserRepository(Database::getConnection());
    $userService = new UserService($userRepository);

    $request = new UserLoginRequest();
    $request->email = 'ahmad56danish@gmail.com';
    $request->password = '999922Dan';

    try {
        $response = $userService->login($request);
        if ($response instanceof UserLoginResponse && password_verify($request->password, $response->user->password)) {
            echo 'Login success';
        }
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

testUserLogin();