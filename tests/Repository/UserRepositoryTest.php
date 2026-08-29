<?php

use app\App\AutoLoader;
use app\App\Database;
use app\Domain\User;
use app\Repository\UserRepository;

require_once __DIR__ . '/../../app/App/AutoLoader.php';
AutoLoader::loadClass();

function testSave()
{
    $userRepository = new UserRepository(Database::getConnection());

    $user = new User();
    $user->name = 'Test';
    $user->email = 'email@gmail.com';
    $user->password = 'rahasia';
    $result = $userRepository->save($user);

    $find = $userRepository->findByEmail('email@gmail.com');
    if ($find !== null) {
        echo 'data berhasil masuk' . PHP_EOL;
    } else {
        'data tidak masuk';
    }

    if ($result instanceof User && $result->name = 'Test') {
        echo 'Test sukses';
    }
}


function testDeleteAll()
{
    $userRepository = new UserRepository(Database::getConnection());
    ;

    if ($userRepository->deleteAll()) {
        echo 'data berhaasil di delete';
    } else {
        echo 'gagal hapus data';
    }
}

testSave();