<?php

namespace app\Model;

class ArticleAddRequest
{
    public ?int $id = null;
    public string $title;
    public string $content;
    public int $userId;
}