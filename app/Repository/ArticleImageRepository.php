<?php

namespace app\Repository;

use app\Domain\ArticleImage;
use PDO;

class ArticleImageRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    function save(ArticleImage $articleImage): ArticleImage
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO article_images(article_id, image, caption)
            VALUES (?, ?, ?)
        ");

        $stmt->execute([
            $articleImage->articleId,
            $articleImage->image,
            $articleImage->caption
        ]);

        $articleImage->id = (int) $this->pdo->lastInsertId();

        return $articleImage;
    }

    function getByArticleId(int $articleId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT id, article_id, image, caption
            FROM article_images
            WHERE article_id = ?
        ");

        $stmt->execute([$articleId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteById(int $id): void
    {
        $stmt = $this->pdo->prepare("
        DELETE FROM article_images
        WHERE id = ?
    ");

        $stmt->execute([$id]);
    }

    public function updateCaption(
        int $id,
        string $caption
    ): void {

        $stmt = $this->pdo->prepare("
        UPDATE article_images
        SET caption = ?
        WHERE id = ?
    ");

        $stmt->execute([
            $caption,
            $id
        ]);
    }


    function deleteByArticleId(int $articleId): void
    {
        $stmt = $this->pdo->prepare("
            DELETE FROM article_images
            WHERE article_id = ?
        ");

        $stmt->execute([$articleId]);
    }

    public function findById(int $id): ?ArticleImage
    {
        $stmt = $this->pdo->prepare("
        SELECT
            id,
            article_id,
            image,
            caption
        FROM article_images
        WHERE id = ?
        LIMIT 1
    ");

        $stmt->execute([$id]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            return null;
        }

        $articleImage = new ArticleImage();

        $articleImage->id = (int) $result['id'];
        $articleImage->articleId = (int) $result['article_id'];
        $articleImage->image = $result['image'];
        $articleImage->caption = $result['caption'];

        return $articleImage;
    }
}