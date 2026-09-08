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
        $stmt = $this->pdo->prepare(" SELECT a.id, a.title, a.slug, a.content, a.view_count, a.status, a.created_at, a.updated_at, GROUP_CONCAT( DISTINCT u.name ORDER BY u.name ASC SEPARATOR ', ' ) AS authors, GROUP_CONCAT( DISTINCT u.img ORDER BY u.name ASC SEPARATOR ',' ) AS author_images, GROUP_CONCAT( DISTINCT t.name ORDER BY t.name ASC SEPARATOR ',' ) AS tags FROM articles a JOIN article_user au ON au.article_id = a.id JOIN users u ON u.id = au.user_id LEFT JOIN article_tag at ON at.article_id = a.id LEFT JOIN tags t ON t.id = at.tag_id WHERE a.slug = ? AND a.deleted_at IS NULL GROUP BY a.id LIMIT 1 ");
        $stmt->execute([$slug]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: false;
    }
    
    public function getByUserId(int $userId): array|false
    {
        $sql = "
        SELECT 
            a.*, 
            GROUP_CONCAT(
                DISTINCT CASE WHEN u.id != ? THEN u.name END 
                ORDER BY u.name ASC 
                SEPARATOR ', '
            ) AS connected_users 
        FROM articles a
        -- Filter artikel yang terhubung dengan user yang sedang login
        JOIN article_user au_current 
            ON au_current.article_id = a.id
        -- Gabungkan kembali untuk mendapatkan user lain (kontributor)
        JOIN article_user au 
            ON au.article_id = a.id
        -- Ambil data nama lengkap user
        JOIN users u 
            ON u.id = au.user_id 
        WHERE 
            au_current.user_id = ? 
            AND a.deleted_at IS NULL 
        GROUP BY 
            a.id 
        ORDER BY 
            a.created_at DESC
    ";

        $stmt = $this->pdo->prepare($sql);

        // Mengeksekusi parameter berurutan sesuai tanda tanya (?) pada query SQL
        $stmt->execute([
            $userId, // Mengisi tanda tanya ke-1: Kondisi pengecualian nama (CASE WHEN u.id != ?)
            $userId  // Mengisi tanda tanya ke-2: Filter artikel user login (au_current.user_id = ?)
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



    function getAndUser(): array
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
                DISTINCT u.name
                ORDER BY u.name ASC
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

        WHERE a.deleted_at IS NULL

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