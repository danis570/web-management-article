<?php

namespace app\Repository;

use app\Domain\User;
use PDO;

class UserRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function save(User $user): User
    {
        $stmt = $this->pdo->prepare("INSERT INTO users
        (id, name, email, password) VALUES (?, ?, ?, ?)");
        $stmt->execute([
            $user->id,
            $user->name,
            $user->email,
            $user->password
        ]);

        return $user;
    }

    public function getAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM users");
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    public function findByEmail(string $email): ?User
    {
        $stmt = $this->pdo->prepare("SELECT id, name, email, password FROM users WHERE email=?");
        $stmt->execute([$email]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            return null;
        }
        $user = new User();
        $user->id = $result['id'];
        $user->name = $result['name'];
        $user->email = $result['email'];
        $user->password = $result['password'];

        return $user;
    }

    public function deleteAll(): int|false
    {
        return $this->pdo->exec("DELETE FROM users");
    }
}