<?php

namespace app\Model;

use app\Domain\UserRole;

class UserRegisterRequest
{
    public string $name;
    public UserRole $role;
    public string $position;
    public string $period;
    public ?string $img = null;
    public string $email;
    public string $password;
}