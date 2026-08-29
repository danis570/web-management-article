<?php

namespace app\Model;

class UserRegisterRequest
{
    public ?int $id = null;
    public string $name;
    public string $email;
    public string $password;
}