<?php

namespace app\App;

class View
{
    private static array $globalData = [];

    public static function share(string $key, mixed $value): void
    {
        self::$globalData[$key] = $value;
    }

    public static function render(string $layout, string $path, array $model)
    {
        $model = array_merge(self::$globalData, $model);

        require_once __DIR__ . "/../View/{$layout}/Layouts/header.php";
        require_once __DIR__ . "/../View{$path}.php";
        require_once __DIR__ . "/../View/{$layout}/Layouts/footer.php";
    }
}
