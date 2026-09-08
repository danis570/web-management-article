<?php

namespace app\Repository;

use app\Domain\ArticleTag;
use PDO;

class ArticleTagRepository
{
    public function __construct(
        private PDO $pdo
    ) {
    }

    public function save(ArticleTag $articleTag): ArticleTag
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO article_tag (
                article_id,
                tag_id
            )
            VALUES (?, ?)
        ");

        $stmt->execute([
            $articleTag->articleId,
            $articleTag->tagId
        ]);

        return $articleTag;
    }

    public function getByArticleId(int $articleId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT
                at.article_id,
                at.tag_id,
                t.name,
                t.slug
            FROM article_tag at
            JOIN tags t
                ON t.id = at.tag_id
            WHERE at.article_id = ?
            ORDER BY t.name ASC
        ");

        $stmt->execute([$articleId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByTagId(int $tagId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT
                at.article_id,
                at.tag_id,
                a.title,
                a.slug
            FROM article_tag at
            JOIN articles a
                ON a.id = at.article_id
            WHERE at.tag_id = ?
              AND a.deleted_at IS NULL
            ORDER BY a.created_at DESC
        ");

        $stmt->execute([$tagId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function delete(
        int $articleId,
        int $tagId
    ): void {
        $stmt = $this->pdo->prepare("
            DELETE FROM article_tag
            WHERE article_id = ?
              AND tag_id = ?
        ");

        $stmt->execute([
            $articleId,
            $tagId
        ]);
    }

    public function deleteByArticleId(int $articleId): void
    {
        $stmt = $this->pdo->prepare("
            DELETE FROM article_tag
            WHERE article_id = ?
        ");

        $stmt->execute([$articleId]);
    }

    public function sync(
        int $articleId,
        array $tagIds
    ): void {
        $this->deleteByArticleId($articleId);

        foreach ($tagIds as $tagId) {

            $tagId = (int) $tagId;

            if ($tagId <= 0) {
                continue;
            }

            $stmt = $this->pdo->prepare("
                INSERT INTO article_tag (
                    article_id,
                    tag_id
                )
                VALUES (?, ?)
            ");

            $stmt->execute([
                $articleId,
                $tagId
            ]);
        }
    }
}