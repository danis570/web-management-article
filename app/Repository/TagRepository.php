<?php

namespace app\Repository;

use app\Domain\Tag;
use PDO;

class TagRepository
{
    public function __construct(
        private PDO $pdo
    ) {
    }

    public function save(Tag $tag): Tag
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO tags (name, slug)
            VALUES (?, ?)
        ");

        $stmt->execute([
            $tag->name,
            $tag->slug
        ]);

        $tag->id = (int) $this->pdo->lastInsertId();

        return $tag;
    }

    public function findById(int $id): Tag|false
    {
        $stmt = $this->pdo->prepare("
            SELECT *
            FROM tags
            WHERE id = ?
        ");

        $stmt->execute([$id]);

        $result = $stmt->fetchObject(Tag::class);

        return $result ?: false;
    }

    public function findBySlug(string $slug): Tag|false
    {
        $stmt = $this->pdo->prepare("
            SELECT *
            FROM tags
            WHERE slug = ?
        ");

        $stmt->execute([$slug]);

        $result = $stmt->fetchObject(Tag::class);

        return $result ?: false;
    }

    public function search(
        string $keyword,
        int $limit = 10
    ): array {
        $stmt = $this->pdo->prepare("
            SELECT id, name, slug
            FROM tags
            WHERE name LIKE ?
            ORDER BY name ASC
            LIMIT ?
        ");

        $search = '%' . $keyword . '%';

        $stmt->bindValue(1, $search, PDO::PARAM_STR);
        $stmt->bindValue(2, $limit, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS, Tag::class);
    }

    public function getAll(): array
    {
        $stmt = $this->pdo->query("
            SELECT *
            FROM tags
            ORDER BY name ASC
        ");

        return $stmt->fetchAll(PDO::FETCH_CLASS, Tag::class);
    }

    public function update(Tag $tag): Tag
    {
        $stmt = $this->pdo->prepare("
            UPDATE tags
            SET name = ?, slug = ?
            WHERE id = ?
        ");

        $stmt->execute([
            $tag->name,
            $tag->slug,
            $tag->id
        ]);

        return $tag;
    }

    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare("
            DELETE FROM tags
            WHERE id = ?
        ");

        $stmt->execute([$id]);
    }
}