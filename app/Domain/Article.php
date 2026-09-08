<?php

namespace app\Domain;

class Article
{
    public ?int $id = null;
    public string $title;
    public string $slug;
    public ?string $content = null;
    public int $viewCount = 0;
    public string $status = 'draft';
    public string $createdAt;
    public string $updatedAt;
    public ?string $deletedAt = null;
}