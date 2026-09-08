<?php

namespace app\Repository;

use app\Domain\ArticleUser;
use app\Domain\User;
use PDO;

class ArticleUserRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    function save(ArticleUser $articleUser): ArticleUser
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO article_user (article_id, user_id)
            VALUES (?, ?)
        ");

        $stmt->execute([
            $articleUser->articleId,
            $articleUser->userId
        ]);

        return $articleUser;
    }

    public function sync(int $articleId, array $userIds): void
    {
        // Hapus seluruh relasi lama
        $stmt = $this->pdo->prepare("
        DELETE FROM article_user
        WHERE article_id = ?
    ");

        $stmt->execute([$articleId]);


        // Tambahkan relasi baru
        if (empty($userIds)) {
            return;
        }

        $stmt = $this->pdo->prepare("
        INSERT INTO article_user (article_id, user_id)
        VALUES (?, ?)
    ");

        foreach ($userIds as $userId) {

            $stmt->execute([
                $articleId,
                (int) $userId
            ]);
        }
    }

    public function getUsersByArticleId(int $articleId): array
    {
        $stmt = $this->pdo->prepare("
        SELECT
            u.id,
            u.name,
            u.email,
            u.img
        FROM article_user au
        JOIN users u
            ON u.id = au.user_id
        WHERE au.article_id = ?
        ORDER BY u.name ASC
    ");

        $stmt->execute([$articleId]);

        return $stmt->fetchAll(PDO::FETCH_CLASS, User::class);
    }

    function getByArticleId(int $articleId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT user_id
            FROM article_user
            WHERE article_id = ?
        ");

        $stmt->execute([$articleId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function getByUserId(int $userId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT article_id
            FROM article_user
            WHERE user_id = ?
        ");

        $stmt->execute([$userId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function delete(int $articleId, int $userId): void
    {
        $stmt = $this->pdo->prepare("
            DELETE FROM article_user
            WHERE article_id = ?
              AND user_id = ?
        ");

        $stmt->execute([
            $articleId,
            $userId
        ]);
    }

    function deleteByArticleId(int $articleId): void
    {
        $stmt = $this->pdo->prepare("
            DELETE FROM article_user
            WHERE article_id = ?
        ");

        $stmt->execute([$articleId]);
    }

    public function exists(int $articleId, int $userId): bool
    {
        $stmt = $this->pdo->prepare("
        SELECT 1
        FROM article_user
        WHERE article_id = ?
        AND user_id = ?
        LIMIT 1
    ");

        $stmt->execute([
            $articleId,
            $userId
        ]);

        return $stmt->fetchColumn() !== false;
    }
}