<?php

$email = 'ahmad@mail.com';

if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo 'benar emailnya';
} else {
    echo 'email tidak valid';
}