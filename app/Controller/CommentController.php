<?php

namespace app\Controller;

use app\App\Database;
use app\Repository\ArticleRepository;
use app\Repository\CommentLikeRepository;
use app\Repository\CommentRepository;
use app\Service\ArticleService;
use app\Service\CommentService;
use app\Service\CommentLikeService;
use Exception;

class CommentController
{
    private CommentService $commentService;
    private CommentLikeService $commentLikeService;
    private ArticleService $articleService;

    public function __construct()
    {
        $pdo = Database::getConnection();

        $commentRepository = new CommentRepository($pdo);
        $commentLikeRepository = new CommentLikeRepository($pdo);
        $articleRepository = new ArticleRepository($pdo);

        $this->commentService = new CommentService(
            $commentRepository
        );

        $this->commentLikeService = new CommentLikeService(
            $pdo,
            $commentLikeRepository,
            $this->commentService
        );

        $this->articleService = new ArticleService(
            $articleRepository
        );
    }

    private function redirectToArticle(int $articleId): void
    {
        $article = $this->articleService->getById($articleId);

        header('Location: /article/' . $article['slug']);
        exit();
    }

    private function getArticleIdFromComment(int $commentId): int
    {
        $comment = $this->commentService->getById($commentId);

        if (!$comment) {
            throw new Exception('Comment not found');
        }

        return $comment->articleId;
    }

    public function postCreate(): void
    {
        if (($_SESSION['login'] ?? false) !== true) {
            header('Location: /login');
            exit();
        }

        try {
            $content = $_POST['content'] ?? '';
            $articleId = (int) ($_POST['article_id'] ?? 0);

            $parentId = isset($_POST['parent_id']) && $_POST['parent_id'] !== ''
                ? (int) $_POST['parent_id']
                : null;

            $userId = (int) ($_SESSION['user_id'] ?? 0);

            if ($userId <= 0) {
                throw new Exception('User session not found');
            }

            if ($articleId <= 0) {
                throw new Exception('Article not found');
            }

            $this->commentService->create(
                $content,
                $articleId,
                $userId,
                $parentId
            );

            $this->redirectToArticle($articleId);

        } catch (\Throwable $e) {
            $_SESSION['error'] = $e->getMessage();

            $articleId = (int) ($_POST['article_id'] ?? 0);

            if ($articleId > 0) {
                $this->redirectToArticle($articleId);
            }

            header('Location: /article');
            exit();
        }
    }

    public function postUpdate(): void
    {
        if (($_SESSION['login'] ?? false) !== true) {
            header('Location: /login');
            exit();
        }

        try {
            $commentId = (int) (
                $_POST['comment_id'] ?? 0
            );

            $content = $_POST['content'] ?? '';

            $userId = (int) (
                $_SESSION['user_id'] ?? 0
            );

            if ($commentId <= 0) {
                throw new Exception('Comment not found');
            }

            $articleId = $this->getArticleIdFromComment(
                $commentId
            );

            $this->commentService->update(
                $commentId,
                $content,
                $userId
            );

            $this->redirectToArticle($articleId);

        } catch (Exception $e) {

            $_SESSION['error'] = $e->getMessage();

            header('Location: /article');
            exit();
        }
    }

    public function postDelete(): void
    {
        if (($_SESSION['login'] ?? false) !== true) {
            header('Location: /login');
            exit();
        }

        try {
            $commentId = (int) (
                $_POST['comment_id'] ?? 0
            );

            $userId = (int) (
                $_SESSION['user_id'] ?? 0
            );

            if ($commentId <= 0) {
                throw new Exception('Comment not found');
            }

            $articleId = $this->getArticleIdFromComment(
                $commentId
            );

            $this->commentService->delete(
                $commentId,
                $userId
            );

            $this->redirectToArticle($articleId);

        } catch (Exception $e) {

            $_SESSION['error'] = $e->getMessage();

            header('Location: /article');
            exit();
        }
    }

    public function postLike(): void
    {
        if (($_SESSION['login'] ?? false) !== true) {
            header('Location: /login');
            exit();
        }

        try {
            $commentId = (int) ($_POST['comment_id'] ?? 0);
            $userId = (int) ($_SESSION['user_id'] ?? 0);

            if ($commentId <= 0) {
                throw new Exception('Comment not found');
            }

            if ($userId <= 0) {
                throw new Exception('User session not found');
            }

            $articleId = $this->getArticleIdFromComment($commentId);

            $this->commentLikeService->like($commentId, $userId);

            $this->redirectToArticle($articleId);

        } catch (\Throwable $e) {

            $_SESSION['error'] = $e->getMessage();

            $commentId = (int) ($_POST['comment_id'] ?? 0);

            if ($commentId > 0) {
                try {
                    $articleId = $this->getArticleIdFromComment($commentId);
                    $this->redirectToArticle($articleId);
                } catch (\Throwable $ignored) {
                }
            }

            header('Location: /article');
            exit();
        }
    }

    public function postUnlike(): void
    {
        if (($_SESSION['login'] ?? false) !== true) {
            header('Location: /login');
            exit();
        }

        try {
            $commentId = (int) ($_POST['comment_id'] ?? 0);
            $userId = (int) ($_SESSION['user_id'] ?? 0);

            if ($commentId <= 0) {
                throw new Exception('Comment not found');
            }

            if ($userId <= 0) {
                throw new Exception('User session not found');
            }

            $articleId = $this->getArticleIdFromComment($commentId);

            $this->commentLikeService->unlike($commentId, $userId);

            $this->redirectToArticle($articleId);

        } catch (\Throwable $e) {

            $_SESSION['error'] = $e->getMessage();

            $commentId = (int) ($_POST['comment_id'] ?? 0);

            if ($commentId > 0) {
                try {
                    $articleId = $this->getArticleIdFromComment($commentId);
                    $this->redirectToArticle($articleId);
                } catch (\Throwable $ignored) {
                }
            }

            header('Location: /article');
            exit();
        }
    }
}