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
}