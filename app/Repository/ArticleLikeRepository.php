<?php

namespace app\Repository;

use app\App\Database;
use app\Domain\ArticleLike;
use PDO;

class ArticleLikeRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    public function save(ArticleLike $articleLike): ArticleLike
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO article_likes (
                article_id,
                visitor_id,
                user_id
            )
            VALUES (?, ?, ?)
        ");

        $stmt->execute([
            $articleLike->articleId,
            $articleLike->visitorId,
            $articleLike->userId,
        ]);

        $articleLike->id = (int) $this->pdo->lastInsertId();

        return $articleLike;
    }

    public function findByArticleAndVisitor(
        int $articleId,
        string $visitorId
    ): ArticleLike|false {

        $stmt = $this->pdo->prepare("
            SELECT
                id,
                article_id,
                visitor_id,
                user_id,
                created_at
            FROM article_likes
            WHERE article_id = ?
              AND visitor_id = ?
            LIMIT 1
        ");

        $stmt->execute([
            $articleId,
            $visitorId,
        ]);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            return false;
        }

        $articleLike = new ArticleLike();

        $articleLike->id = (int) $data['id'];
        $articleLike->articleId = (int) $data['article_id'];
        $articleLike->visitorId = $data['visitor_id'];

        $articleLike->userId = $data['user_id'] !== null
            ? (int) $data['user_id']
            : null;

        $articleLike->createdAt = $data['created_at'];

        return $articleLike;
    }

    public function deleteByArticleAndVisitor(
        int $articleId,
        string $visitorId
    ): void {

        $stmt = $this->pdo->prepare("
            DELETE FROM article_likes
            WHERE article_id = ?
              AND visitor_id = ?
        ");

        $stmt->execute([
            $articleId,
            $visitorId,
        ]);
    }
}