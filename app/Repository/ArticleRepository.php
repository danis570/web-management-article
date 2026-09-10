<?php

namespace app\Repository;

use app\Domain\Article;
use PDO;

class ArticleRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    function save(Article $article): Article
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO articles(title, slug, content, status)
            VALUES (?, ?, ?, ?)
        ");

        $stmt->execute([
            $article->title,
            $article->slug,
            $article->content,
            $article->status
        ]);

        $article->id = (int) $this->pdo->lastInsertId();

        return $article;
    }

    function update(Article $article): Article
    {
        $stmt = $this->pdo->prepare("
            UPDATE articles
            SET title = ?, slug = ?, content = ?, status = ?
            WHERE id = ?
        ");

        $stmt->execute([
            $article->title,
            $article->slug,
            $article->content,
            $article->status,
            $article->id
        ]);

        return $article;
    }
    public function getBySlug(string $slug): array|false
    {
        $stmt = $this->pdo->prepare("
        SELECT
            a.id,
            a.title,
            a.slug,
            a.content,
            a.view_count,
            a.status,
            a.created_at,
            a.updated_at,

            GROUP_CONCAT(
                DISTINCT p.name
                ORDER BY p.name ASC
                SEPARATOR ', '
            ) AS authors,

            GROUP_CONCAT(
                DISTINCT p.img
                ORDER BY p.name ASC
                SEPARATOR ','
            ) AS author_images

        FROM articles a

        JOIN article_user au
            ON au.article_id = a.id

        JOIN users u
            ON u.id = au.user_id

        JOIN profiles p
            ON p.user_id = u.id

        WHERE
            a.slug = ?
            AND a.deleted_at IS NULL

        GROUP BY a.id

        LIMIT 1
    ");

        $stmt->execute([$slug]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            return false;
        }

        // Ambil tags artikel
        $tagStmt = $this->pdo->prepare("
        SELECT
            t.id,
            t.name,
            t.slug

        FROM article_tag at

        JOIN tags t
            ON t.id = at.tag_id

        WHERE at.article_id = ?

        ORDER BY t.name ASC
    ");

        $tagStmt->execute([
            (int) $result['id']
        ]);

        $result['tags'] = $tagStmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    public function getByTag(string $tagSlug): array
    {
        $stmt = $this->pdo->prepare("
        SELECT
            a.id,
            a.title,
            a.slug,
            a.content,
            a.view_count,
            a.status,
            a.created_at,
            a.updated_at,

            GROUP_CONCAT(
                DISTINCT p.name
                ORDER BY p.name ASC
                SEPARATOR ', '
            ) AS authors,

            GROUP_CONCAT(
                DISTINCT p.img
                ORDER BY p.name ASC
                SEPARATOR ','
            ) AS author_images

        FROM articles a

        JOIN article_tag at
            ON at.article_id = a.id

        JOIN tags t
            ON t.id = at.tag_id

        JOIN article_user au
            ON au.article_id = a.id

        JOIN users u
            ON u.id = au.user_id

        JOIN profiles p
            ON p.user_id = u.id

        WHERE
            t.slug = ?
            AND a.status = 'published'
            AND a.deleted_at IS NULL

        GROUP BY a.id

        ORDER BY a.created_at DESC
    ");

        $stmt->execute([$tagSlug]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByUserId(int $userId): array|false
    {
        $sql = "
        SELECT
            a.*,

            GROUP_CONCAT(
                DISTINCT CASE
                    WHEN u.id != ? THEN p.name
                END
                ORDER BY p.name ASC
                SEPARATOR ', '
            ) AS connected_users

        FROM articles a

        -- Memastikan artikel terhubung
        -- dengan user yang sedang login
        JOIN article_user au_current
            ON au_current.article_id = a.id

        -- Mengambil seluruh user yang terhubung
        -- dengan artikel tersebut
        JOIN article_user au
            ON au.article_id = a.id

        -- Account user
        JOIN users u
            ON u.id = au.user_id

        -- Profile user
        JOIN profiles p
            ON p.user_id = u.id

        WHERE
            au_current.user_id = ?
            AND a.deleted_at IS NULL

        GROUP BY a.id

        ORDER BY a.created_at DESC
    ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            $userId,
            $userId
        ]);

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return !empty($result) ? $result : false;
    }



    function getById(int $id): array|false
    {
        $stmt = $this->pdo->prepare("
            SELECT id, title, slug, content, view_count, status,
                   created_at, updated_at, deleted_at
            FROM articles
            WHERE id = ?
        ");

        $stmt->execute([$id]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ?: false;
    }

    function getAll(): array|false
    {
        $stmt = $this->pdo->query("
            SELECT id, title, slug, content, view_count, status,
                   created_at, updated_at, deleted_at
            FROM articles
            WHERE deleted_at IS NULL
            ORDER BY created_at DESC
        ");

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result ?: false;
    }



    public function getAndUser(): array
    {
        $stmt = $this->pdo->query("
        SELECT
            a.id,
            a.title,
            a.slug,
            a.content,
            a.view_count,
            a.status,
            a.created_at,
            a.updated_at,

            GROUP_CONCAT(
                DISTINCT p.name
                ORDER BY p.name ASC
                SEPARATOR ', '
            ) AS authors,

            (
                SELECT ai.image
                FROM article_images ai
                WHERE ai.article_id = a.id
                ORDER BY ai.id ASC
                LIMIT 1
            ) AS image

        FROM articles a

        JOIN article_user au
            ON au.article_id = a.id

        JOIN users u
            ON u.id = au.user_id

        JOIN profiles p
            ON p.user_id = u.id

        WHERE
            a.status = 'published'
            AND a.deleted_at IS NULL

        GROUP BY a.id

        ORDER BY a.created_at DESC
    ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function incrementViewCount(int $id): void
    {
        $stmt = $this->pdo->prepare("
            UPDATE articles
            SET view_count = view_count + 1
            WHERE id = ?
        ");

        $stmt->execute([$id]);
    }

    function deleteById(int $id): void
    {
        $stmt = $this->pdo->prepare("
            DELETE FROM articles
            WHERE id = ?
        ");

        $stmt->execute([$id]);
    }

    function deleteAll(): void
    {
        $this->pdo->exec("DELETE FROM articles");
    }

    function findById(int $id): Article|false
    {
        $stmt = $this->pdo->prepare("
            SELECT id, title, slug, content, view_count, status,
                   created_at, updated_at, deleted_at
            FROM articles
            WHERE id = ?
        ");

        $stmt->execute([$id]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            return false;
        }

        $response = new Article();

        $response->id = (int) $result['id'];
        $response->title = $result['title'];
        $response->slug = $result['slug'];
        $response->content = $result['content'];
        $response->viewCount = (int) $result['view_count'];
        $response->status = $result['status'];
        $response->createdAt = $result['created_at'];
        $response->updatedAt = $result['updated_at'];
        $response->deletedAt = $result['deleted_at'];

        return $response;
    }
}