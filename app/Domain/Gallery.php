<?php

namespace app\Domain;

class Gallery
{
    public ?int $id = null;

    public int $userId;

    public ?string $caption = null;

    public string $slug;

    public string $createdAt;

    public string $updatedAt;

    public ?string $deletedAt = null;
}