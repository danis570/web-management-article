<?php

namespace app\Domain;

class ArticleView
{
    public ?int $id = null;

    public int $articleId;

    public string $visitorId;

    public ?int $userId = null;

    public ?string $createdAt = null;
}