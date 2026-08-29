<?php

use app\Controller\HomeController;

spl_autoload_register(function ($className) {
    echo $className . PHP_EOL;
    require_once __DIR__ . '/../' . $className . '.php';
});

$instance = new HomeController();
$instance->index();