<?php

namespace app\Repository;

use app\App\Database;
use app\Domain\ArticleView;
use PDO;

class ArticleViewRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    public function save(ArticleView $articleView): ArticleView
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO article_views (
                article_id,
                visitor_id,
                user_id
            )
            VALUES (?, ?, ?)
        ");

        $stmt->execute([
            $articleView->articleId,
            $articleView->visitorId,
            $articleView->userId,
        ]);

        $articleView->id = (int) $this->pdo->lastInsertId();

        return $articleView;
    }

    public function findByArticleAndVisitor(
        int $articleId,
        string $visitorId
    ): ArticleView|false {

        $stmt = $this->pdo->prepare("
            SELECT
                id,
                article_id,
                visitor_id,
                user_id,
                created_at
            FROM article_views
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

        $articleView = new ArticleView();

        $articleView->id = (int) $data['id'];
        $articleView->articleId = (int) $data['article_id'];
        $articleView->visitorId = $data['visitor_id'];
        $articleView->userId = $data['user_id'] !== null
            ? (int) $data['user_id']
            : null;
        $articleView->createdAt = $data['created_at'];

        return $articleView;
    }
}