<?php

namespace app\Domain;

class User
{
    public ?int $id = null;
    public string $name;
    public string $email;
    public string $password;
}