<?php

namespace app\Domain;

class User
{
    public ?int $id = null;
    public UserRole $role;
    public string $email;
    public string $password;
}