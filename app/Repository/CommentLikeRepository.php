<?php

namespace app\Repository;

use app\Domain\CommentLike;
use PDO;

class CommentLikeRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function save(CommentLike $commentLike): CommentLike
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO comment_likes (
                comment_id,
                user_id
            )
            VALUES (?, ?)
        ");

        $stmt->execute([
            $commentLike->commentId,
            $commentLike->userId
        ]);

        $commentLike->id = (int) $this->pdo->lastInsertId();

        return $commentLike;
    }

    public function findByCommentAndUser(
        int $commentId,
        int $userId
    ): CommentLike|false {
        $stmt = $this->pdo->prepare("
            SELECT
                id,
                comment_id,
                user_id,
                created_at
            FROM comment_likes
            WHERE comment_id = ?
              AND user_id = ?
        ");

        $stmt->execute([
            $commentId,
            $userId
        ]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return false;
        }

        return $this->mapToDomain($row);
    }

    public function deleteByCommentAndUser(
        int $commentId,
        int $userId
    ): bool {
        $stmt = $this->pdo->prepare("
            DELETE FROM comment_likes
            WHERE comment_id = ?
              AND user_id = ?
        ");

        return $stmt->execute([
            $commentId,
            $userId
        ]);
    }

    public function countByCommentId(int $commentId): int
    {
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*)
            FROM comment_likes
            WHERE comment_id = ?
        ");

        $stmt->execute([$commentId]);

        return (int) $stmt->fetchColumn();
    }

    private function mapToDomain(array $row): CommentLike
    {
        $commentLike = new CommentLike();

        $commentLike->id = (int) $row['id'];
        $commentLike->commentId = (int) $row['comment_id'];
        $commentLike->userId = (int) $row['user_id'];
        $commentLike->createdAt = $row['created_at'];

        return $commentLike;
    }
}