<?php

namespace app\Repository;

use app\Domain\Session;
use PDO;

class SessionRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function save(Session $session): Session
    {
        $sql = "
            INSERT INTO sessions (
                id,
                user_id,
                ip_address,
                user_agent,
                last_activity,
                expires_at
            )
            VALUES (?, ?, ?, ?, ?, ?)
        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            $session->id,
            $session->userId,
            $session->ipAddress,
            $session->userAgent,
            $session->lastActivity,
            $session->expiresAt
        ]);

        return $session;
    }

    public function findById(string $id): ?Session
    {
        $sql = "
            SELECT
                id,
                user_id,
                ip_address,
                user_agent,
                last_activity,
                expires_at
            FROM sessions
            WHERE id = ?
            LIMIT 1
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            return null;
        }

        $session = new Session();

        $session->id = $data['id'];
        $session->userId = (int) $data['user_id'];
        $session->ipAddress = $data['ip_address'];
        $session->userAgent = $data['user_agent'];
        $session->lastActivity = $data['last_activity'];
        $session->expiresAt = $data['expires_at'];

        return $session;
    }

    public function deleteById(string $id): void
    {
        $sql = "
            DELETE FROM sessions
            WHERE id = ?
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
    }

    public function deleteByUserId(int $userId): void
    {
        $sql = "
            DELETE FROM sessions
            WHERE user_id = ?
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$userId]);
    }

    public function updateLastActivity(string $id, string $lastActivity): void
    {
        $sql = "
            UPDATE sessions
            SET last_activity = ?
            WHERE id = ?
        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            $lastActivity,
            $id
        ]);
    }

    public function deleteExpired(): void
    {
        $sql = "
            DELETE FROM sessions
            WHERE expires_at <= CURRENT_TIMESTAMP
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
    }
}
