<?php

namespace app\Domain;

class Article
{
    public ?int $id = null;
    public string $title;
    public string $content;
    public int $userId;

}