<?php

namespace app\Model;

use app\Domain\UserRole;

class UserRegisterRequest
{
    public UserRole $role;

    public string $email;

    public string $password;

    public UserProfileRegisterRequest $profile;
}