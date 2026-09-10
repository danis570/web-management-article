<?php

namespace app\Domain;

class ArticleLike
{
    public ?int $id = null;

    public int $articleId;

    public string $visitorId;

    public ?int $userId = null;

    public ?string $createdAt = null;
}