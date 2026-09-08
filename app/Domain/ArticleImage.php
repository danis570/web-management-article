<?php

namespace app\Domain;

class ArticleImage
{
    public ?int $id = null;
    public int $articleId;
    public string $image;
    public ?string $caption = null;
}