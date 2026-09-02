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
        $stmt = $this->pdo->prepare("INSERT INTO articles(id, title, content, user_id) 
        VALUES (?, ?, ?, ?)");
        $stmt->execute([
            $article->id,
            $article->title,
            $article->content,
            $article->userId
        ]);

        return $article;
    }

    function updateContent(Article $article): Article
    {
        $stmt = $this->pdo->prepare("UPDATE articles SET content=? 
        WHERE id=?");
        $stmt->execute([
            $article->content,
            $article->id,
        ]);

        return $article;
    }

    function getByUserId(int $userId): array|false
    {
        $stmt = $this->pdo->prepare("SELECT * FROM articles WHERE user_id=?");
        $stmt->execute([$userId]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($result) {
            return $result;
        } else {
            return false;
        }

    }

    function getAll(): array|false
    {
        $stmt = $this->pdo->query("SELECT id, title, content, user_id 
        FROM articles");
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($result) {
            return $result;
        } else {
            return false;
        }
    }

    function deleteAll()
    {
        $this->pdo->exec("DELETE FROM articles");
    }

    function findById(int $id): Article
    {
        $stmt = $this->pdo->prepare("SELECT id, title, content, user_id
        FROM articles WHERE id=?");
        $stmt->execute([
            $id
        ]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $response = new Article();
        $response->id = $result['id'];
        $response->title = $result['title'];
        $response->content = $result['content'];
        $response->userId = $result['user_id'];
        return $response;

    }
}