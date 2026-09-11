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
        $stmt = $this->pdo->prepare("
            INSERT INTO users
            (role, email, password)
            VALUES (?, ?, ?)
        ");

        $stmt->execute([
            $user->role->value,
            $user->email,
            $user->password
        ]);

        $user->id = (int) $this->pdo->lastInsertId();

        return $user;
    }

    public function getAll(): array
    {
        $stmt = $this->pdo->query("
            SELECT
                u.id,
                u.role,
                u.email,
                u.password,

                p.name,
                p.position,
                p.period,
                p.img

            FROM users u

            JOIN profiles p
                ON p.user_id = u.id

            ORDER BY p.name ASC
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById(int $id): ?User
    {
        $stmt = $this->pdo->prepare("
        SELECT
            id,
            role,
            email,
            password

        FROM users

        WHERE id = ?

        LIMIT 1
    ");

        $stmt->execute([$id]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result === false) {
            return null;
        }

        $user = new User();

        $user->id = (int) $result['id'];
        $user->role = UserRole::from($result['role']);
        $user->email = $result['email'];
        $user->password = $result['password'];

        return $user;
    }


    public function search(
        string $keyword,
        int $excludeUserId,
        int $limit = 10
    ): array {
        $keyword = trim($keyword);

        if ($keyword === '') {
            return [];
        }

        $limit = max(1, min($limit, 50));

        $stmt = $this->pdo->prepare("
        SELECT
            u.id,
            p.name,
            u.email,
            p.img

        FROM users u

        JOIN profiles p
            ON p.user_id = u.id

        WHERE
            u.id != ?
            AND u.role != 'admin'
            AND (
                p.name LIKE ?
                OR u.email LIKE ?
            )

        ORDER BY p.name ASC

        LIMIT $limit
    ");

        $search = '%' . $keyword . '%';

        $stmt->execute([
            $excludeUserId,
            $search,
            $search
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findByEmail(string $email): ?User
    {
        $stmt = $this->pdo->prepare("
            SELECT
                id,
                role,
                email,
                password

            FROM users

            WHERE email = ?

            LIMIT 1
        ");

        $stmt->execute([$email]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result === false) {
            return null;
        }

        $user = new User();

        $user->id = (int) $result['id'];
        $user->role = UserRole::from($result['role']);
        $user->email = $result['email'];
        $user->password = $result['password'];

        return $user;
    }

    public function deleteAll(): int|false
    {
        return $this->pdo->exec("
            DELETE FROM users
        ");
    }
}