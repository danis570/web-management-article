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

    function save(Comment $comment): Comment
    {
        $stmt = $this->pdo->prepare("INSERT INTO 
        comments(content, parent_id, article_id, user_id) VALUES (?, ?, ?, ?)");
        $stmt->execute([
            $comment->content,
            $comment->parentId,
            $comment->articleId,
            $comment->userId
        ]);

        return $comment;
    }

    function deleteAll()
    {
        return $this->pdo->exec("DELETE FROM comments");
    }
}