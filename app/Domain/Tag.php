<?php

namespace app\Domain;

class Tag
{
    public ?int $id = null;
    public string $name;
    public string $slug;
    public ?string $createdAt = null;
}