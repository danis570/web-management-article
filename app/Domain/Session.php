<?php

namespace app\Domain;

class Session
{
    public string $id;
    public int $userId;
    public ?string $ipAddress = null;
    public ?string $userAgent = null;
    public string $lastActivity;
    public string $expiresAt;
}
