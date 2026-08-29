<?php

require_once __DIR__ . '/conn.php';

function getArticles(PDO $pdo): array
{
    $stmt = $pdo->prepare("SELECT id, name, email, password FROM users;");
    $stmt->execute();

    $users = $stmt->fetchAll();
    return $users;
}

