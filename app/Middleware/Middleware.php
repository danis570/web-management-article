<?php

namespace app\Middleware;

class Middleware
{
    function guestOnly()
    {
        if (($_SESSION['login'] ?? false) == true) {
            header('Location: /');
            exit();
        }
    }
    function userOnly()
    {
        if (($_SESSION['login'] ?? false) != true) {
            header('Location: /login');
            exit();
        }

        if ($_SESSION['admin'] == true) {
            header('Location: /');
            exit();
        }
    }

    function adminOnly()
    {
        if (($_SESSION['login'] ?? false) != true || ($_SESSION['admin'] ?? false) != true) {
            header('Location: /');
            exit();
        }
    }

    function userAndAdmin()
    {
        if (($_SESSION['login'] ?? false) != true) {
            header('Location: /login');
            exit();
        }
    }
}