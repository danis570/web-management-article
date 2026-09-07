<?php

namespace app\Domain;

class User
{
    public ?int $id = null;
    public string $name;
    public UserRole $role;
    public string $position;
    public string $period;
    public ?string $img = null;
    public string $email;
    public string $password;
}