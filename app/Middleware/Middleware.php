<?php

namespace app\Middleware;

class Middleware
{
    function userNotLogged()
    {
        if (($_SESSION['login'] ?? false) == true) {
            header('Location: /');
            exit();
        }
    }
    function userHasLogged()
    {
        if (($_SESSION['login'] ?? false) != true) {
            header('Location: /login');
            exit();
        }
    }
}