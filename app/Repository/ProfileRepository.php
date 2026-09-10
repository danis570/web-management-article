<?php

namespace app\Repository;

use app\Domain\Profile;
use PDO;

class ProfileRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function save(Profile $profile): Profile
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO profiles
            (user_id, name, position, period, img)
            VALUES (?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            $profile->userId,
            $profile->name,
            $profile->position,
            $profile->period,
            $profile->img
        ]);

        $profile->id = (int) $this->pdo->lastInsertId();

        return $profile;
    }

    public function findByUserId(int $userId): ?Profile
    {
        $stmt = $this->pdo->prepare("
            SELECT
                id,
                user_id,
                name,
                position,
                period,
                img
            FROM profiles
            WHERE user_id = ?
            LIMIT 1
        ");

        $stmt->execute([$userId]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result === false) {
            return null;
        }

        $profile = new Profile();

        $profile->id = (int) $result['id'];
        $profile->userId = (int) $result['user_id'];
        $profile->name = $result['name'];
        $profile->position = $result['position'];
        $profile->period = $result['period'];
        $profile->img = $result['img'];

        return $profile;
    }

    public function update(Profile $profile): Profile
    {
        $stmt = $this->pdo->prepare("
            UPDATE profiles
            SET
                name = ?,
                position = ?,
                period = ?,
                img = ?
            WHERE user_id = ?
        ");

        $stmt->execute([
            $profile->name,
            $profile->position,
            $profile->period,
            $profile->img,
            $profile->userId
        ]);

        return $profile;
    }

    public function deleteByUserId(int $userId): void
    {
        $stmt = $this->pdo->prepare("
            DELETE FROM profiles
            WHERE user_id = ?
        ");

        $stmt->execute([$userId]);
    }
}