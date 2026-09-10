<?php

namespace app\Repository;

use app\Domain\Comment;
use PDO;

class CommentRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function save(Comment $comment): Comment
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO comments (
                content,
                parent_id,
                article_id,
                user_id
            )
            VALUES (?, ?, ?, ?)
        ");

        $stmt->execute([
            $comment->content,
            $comment->parentId,
            $comment->articleId,
            $comment->userId
        ]);

        $comment->id = (int) $this->pdo->lastInsertId();

        return $comment;
    }

    public function findById(int $id): Comment|false
    {
        $stmt = $this->pdo->prepare("
            SELECT
                id,
                content,
                parent_id,
                article_id,
                user_id,
                like_count,
                created_at,
                updated_at,
                deleted_at
            FROM comments
            WHERE id = ?
        ");

        $stmt->execute([$id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return false;
        }

        return $this->mapToDomain($row);
    }

    public function getByArticleId(int $articleId, int $userId = 0): array
    {
        $stmt = $this->pdo->prepare("
        SELECT
            c.id,
            c.content,
            c.parent_id,
            c.article_id,
            c.user_id,
            c.like_count,
            c.created_at,
            c.updated_at,
            c.deleted_at,

            p.name,
            p.position,
            p.img,

            CASE
                WHEN cl.id IS NULL THEN 0
                ELSE 1
            END AS is_liked

        FROM comments c

        JOIN profiles p
            ON p.user_id = c.user_id

        LEFT JOIN comment_likes cl
            ON cl.comment_id = c.id
            AND cl.user_id = ?

        WHERE
            c.article_id = ?
            AND c.deleted_at IS NULL

        ORDER BY c.created_at ASC
    ");

        $stmt->execute([
            $userId,
            $articleId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByUserId(int $userId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT
                id,
                content,
                parent_id,
                article_id,
                user_id,
                like_count,
                created_at,
                updated_at,
                deleted_at
            FROM comments
            WHERE user_id = ?
              AND deleted_at IS NULL
            ORDER BY created_at DESC
        ");

        $stmt->execute([$userId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update(Comment $comment): bool
    {
        $stmt = $this->pdo->prepare("
            UPDATE comments
            SET
                content = ?,
                updated_at = CURRENT_TIMESTAMP
            WHERE id = ?
        ");

        return $stmt->execute([
            $comment->content,
            $comment->id
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("
            UPDATE comments
            SET deleted_at = CURRENT_TIMESTAMP
            WHERE id = ?
        ");

        return $stmt->execute([$id]);
    }

    public function incrementLikeCount(int $commentId): bool
    {
        $stmt = $this->pdo->prepare("
            UPDATE comments
            SET like_count = like_count + 1
            WHERE id = ?
        ");

        return $stmt->execute([$commentId]);
    }

    public function decrementLikeCount(int $commentId): bool
    {
        $stmt = $this->pdo->prepare("
            UPDATE comments
            SET like_count = GREATEST(like_count - 1, 0)
            WHERE id = ?
        ");

        return $stmt->execute([$commentId]);
    }

    private function mapToDomain(array $row): Comment
    {
        $comment = new Comment();

        $comment->id = (int) $row['id'];
        $comment->content = $row['content'];
        $comment->parentId = $row['parent_id'] !== null
            ? (int) $row['parent_id']
            : null;
        $comment->articleId = (int) $row['article_id'];
        $comment->userId = (int) $row['user_id'];
        $comment->likeCount = (int) $row['like_count'];
        $comment->createdAt = $row['created_at'];
        $comment->updatedAt = $row['updated_at'];
        $comment->deletedAt = $row['deleted_at'];

        return $comment;
    }
}