<?php

namespace app\App;

class View
{
    static function renderPublic(string $path, array $model)
    {
        require_once __DIR__ . '/../View/Public/Layouts/header.php';
        require_once __DIR__ . "/../View/Public$path.php";
        require_once __DIR__ . '/../View/Public/Layouts/footer.php';
    }

    static function renderUser(string $path, array $model)
    {
        require_once __DIR__ . '/../View/User/Layouts/header.php';
        require_once __DIR__ . "/../View/User$path.php";
        require_once __DIR__ . '/../View/User/Layouts/footer.php';
    }

    static function renderAdmin(string $path, array $model)
    {
        require_once __DIR__ . '/../View/Admin/Layouts/header.php';
        require_once __DIR__ . "/../View/Admin$path.php";
        require_once __DIR__ . '/../View/Admin/Layouts/footer.php';
    }
}