<?php

namespace app\Domain;

class Comment
{
    public ?int $id = null;
    public string $content;
    public ?int $parentId = null;
    public int $articleId;
    public int $userId;
    public int $likeCount = 0;
    public ?string $createdAt = null;
    public ?string $updatedAt = null;
    public ?string $deletedAt = null;
}