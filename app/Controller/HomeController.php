<?php

namespace app\Controller;

use app\App\View;

class HomeController
{
    function index()
    {
        View::renderPublic('/index', [
            'title' => 'Blog App - by: Danish'
        ]);
    }
}