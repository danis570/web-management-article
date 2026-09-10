<?php

namespace app\Domain;

class CommentLike
{
    public ?int $id = null;
    public int $commentId;
    public int $userId;
    public ?string $createdAt = null;
}