<?php

enum UserRole: string{
    case ADMIN = 'admin';
    case USER = 'user';
}

$role = UserRole::USER;

print_r($role->value);
print_r(UserRole::ADMIN);
