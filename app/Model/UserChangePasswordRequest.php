<?php

namespace app\Model;

class UserChangePasswordRequest
{
    public int $userId;
    public string $currentPassword;
    public string $newPassword;
}
