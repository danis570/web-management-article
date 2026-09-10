<?php

namespace app\Domain;

class Profile
{
    public ?int $id = null;
    public int $userId;
    public string $name;
    public string $position;
    public string $period;
    public ?string $img = null;
}