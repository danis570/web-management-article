<?php

namespace app\Repository;

use app\Domain\User;
use app\Domain\UserRole;
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
        (id, name, role, position, period, img, email, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $user->id,
            $user->name,
            $user->role->value,
            $user->position,
            $user->period,
            $user->img,
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


    public function search(
        string $keyword,
        int $excludeUserId,
        int $limit = 10
    ): array {
        $stmt = $this->pdo->prepare("
        SELECT id, name, email, img
        FROM users
        WHERE id != ?
          AND role != 'admin'
          AND (
              name LIKE ?
              OR email LIKE ?
          )
        ORDER BY name ASC
        LIMIT ?
    ");

        $search = '%' . $keyword . '%';

        $stmt->bindValue(1, $excludeUserId, PDO::PARAM_INT);
        $stmt->bindValue(2, $search, PDO::PARAM_STR);
        $stmt->bindValue(3, $search, PDO::PARAM_STR);
        $stmt->bindValue(4, $limit, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS, User::class);
    }



    public function findByEmail(string $email): ?User
    {
        $stmt = $this->pdo->prepare("SELECT id, name, role, position, period, img, email, password FROM users WHERE email=?");
        $stmt->execute([$email]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            return null;
        }
        $user = new User();
        $user->id = $result['id'];
        $user->name = $result['name'];
        $user->role = UserRole::from($result['role']);
        $user->position = $result['position'];
        $user->period = $result['period'];
        $user->img = $result['img'];
        $user->email = $result['email'];
        $user->password = $result['password'];

        return $user;
    }

    public function deleteAll(): int|false
    {
        return $this->pdo->exec("DELETE FROM users");
    }
}