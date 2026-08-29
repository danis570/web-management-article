<?php

require_once __DIR__ . '/conn.php';

function getUsers(PDO $pdo): array
{
    $stmt = $pdo->prepare("SELECT id, name, email, password FROM users;");
    $stmt->execute();

    $users = $stmt->fetchAll();
    return $users;
}

function getUserById(PDO $pdo, int $id)
{
    $stmt = $pdo->prepare("SELECT id, name, email, password FROM users WHERE id = ?;");
    $stmt->execute([$id]);

    $user = $stmt->fetch();
    return $user;
}