<?php

namespace app\Domain;

enum UserRole: string
{
    case ADMIN = 'admin';
    case USER = 'user';
}