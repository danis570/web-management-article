<?php

namespace app\Controller;

use app\App\Database;
use app\Repository\ArticleRepository;
use app\Repository\CommentLikeRepository;
use app\Repository\CommentRepository;
use app\Repository\SessionRepository;
use app\Service\ArticleService;
use app\Service\CommentLikeService;
use app\Service\CommentService;
use app\Service\SessionService;
use Exception;

class CommentController
{
    private CommentService $commentService;
    private CommentLikeService $commentLikeService;
    private ArticleService $articleService;
    private SessionService $sessionService;

    public function __construct()
    {
        $pdo = Database::getConnection();

        /*
        |--------------------------------------------------------------------------
        | Repositories
        |--------------------------------------------------------------------------
        */

        $commentRepository = new CommentRepository($pdo);

        $commentLikeRepository =
            new CommentLikeRepository($pdo);

        $articleRepository =
            new ArticleRepository($pdo);

        $sessionRepository =
            new SessionRepository($pdo);


        /*
        |--------------------------------------------------------------------------
        | Services
        |--------------------------------------------------------------------------
        */

        $this->commentService =
            new CommentService(
                $commentRepository
            );


        $this->commentLikeService =
            new CommentLikeService(
                $pdo,
                $commentLikeRepository,
                $this->commentService
            );


        $this->articleService =
            new ArticleService(
                $articleRepository
            );


        $this->sessionService =
            new SessionService(
                $sessionRepository
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Current User ID
    |--------------------------------------------------------------------------
    */

    private function getCurrentUserId(): int
    {
        $userId =
            $this->sessionService->getCurrentUserId();

        if ($userId === null) {
            throw new Exception(
                'User session not found.'
            );
        }

        return $userId;
    }


    /*
    |--------------------------------------------------------------------------
    | Redirect To Article
    |--------------------------------------------------------------------------
    */

    private function redirectToArticle(int $articleId): void
    {
        if ($articleId <= 0) {
            header('Location: /article');
            exit();
        }


        $article =
            $this->articleService->getById(
                $articleId
            );


        if (!$article) {
            header('Location: /article');
            exit();
        }


        header(
            'Location: /article/' .
            $article['slug']
        );

        exit();
    }


    /*
    |--------------------------------------------------------------------------
    | Get Article ID From Comment
    |--------------------------------------------------------------------------
    */

    private function getArticleIdFromComment(
        int $commentId
    ): int {

        if ($commentId <= 0) {
            throw new Exception(
                'Comment not found.'
            );
        }


        $comment =
            $this->commentService->getById(
                $commentId
            );


        if (!$comment) {
            throw new Exception(
                'Comment not found.'
            );
        }


        return $comment->articleId;
    }


    /*
    |--------------------------------------------------------------------------
    | Redirect Back To Comment Article
    |--------------------------------------------------------------------------
    */

    private function redirectToCommentArticle(
        int $commentId
    ): void {

        try {

            $articleId =
                $this->getArticleIdFromComment(
                    $commentId
                );


            $this->redirectToArticle(
                $articleId
            );

        } catch (\Throwable $e) {

            header('Location: /article');
            exit();
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Store Error And Redirect
    |--------------------------------------------------------------------------
    */

    private function handleError(
        string $message,
        ?int $articleId = null,
        ?int $commentId = null
    ): void {

        /*
         * Flash/error sementara masih menggunakan
         * PHP session.
         *
         * Nanti bisa kita migrasikan juga.
         */

        $_SESSION['error'] = $message;


        /*
         * Kalau sudah tahu article ID,
         * langsung kembali ke artikel.
         */

        if ($articleId !== null && $articleId > 0) {

            $this->redirectToArticle(
                $articleId
            );
        }


        /*
         * Kalau hanya punya comment ID,
         * cari artikel dari comment.
         */

        if ($commentId !== null && $commentId > 0) {

            $this->redirectToCommentArticle(
                $commentId
            );
        }


        header('Location: /article');
        exit();
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE COMMENT
    |--------------------------------------------------------------------------
    */

    public function postCreate(): void
    {
        try {

            /*
            |--------------------------------------------------------------------------
            | Current User
            |--------------------------------------------------------------------------
            */

            $userId =
                $this->getCurrentUserId();


            /*
            |--------------------------------------------------------------------------
            | Request
            |--------------------------------------------------------------------------
            */

            $content =
                trim(
                    $_POST['content'] ?? ''
                );


            $articleId =
                (int) (
                    $_POST['article_id'] ?? 0
                );


            $parentId =
                isset($_POST['parent_id']) &&
                $_POST['parent_id'] !== ''
                    ? (int) $_POST['parent_id']
                    : null;


            /*
            |--------------------------------------------------------------------------
            | Validation
            |--------------------------------------------------------------------------
            */

            if ($articleId <= 0) {
                throw new Exception(
                    'Article not found.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Create
            |--------------------------------------------------------------------------
            */

            $this->commentService->create(
                $content,
                $articleId,
                $userId,
                $parentId
            );


            /*
            |--------------------------------------------------------------------------
            | Redirect
            |--------------------------------------------------------------------------
            */

            $this->redirectToArticle(
                $articleId
            );

        } catch (\Throwable $e) {

            $articleId =
                (int) (
                    $_POST['article_id'] ?? 0
                );


            $this->handleError(
                $e->getMessage(),
                $articleId
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE COMMENT
    |--------------------------------------------------------------------------
    */

    public function postUpdate(): void
    {
        try {

            /*
            |--------------------------------------------------------------------------
            | Current User
            |--------------------------------------------------------------------------
            */

            $userId =
                $this->getCurrentUserId();


            /*
            |--------------------------------------------------------------------------
            | Request
            |--------------------------------------------------------------------------
            */

            $commentId =
                (int) (
                    $_POST['comment_id'] ?? 0
                );


            $content =
                trim(
                    $_POST['content'] ?? ''
                );


            /*
            |--------------------------------------------------------------------------
            | Validation
            |--------------------------------------------------------------------------
            */

            if ($commentId <= 0) {
                throw new Exception(
                    'Comment not found.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Article
            |--------------------------------------------------------------------------
            */

            $articleId =
                $this->getArticleIdFromComment(
                    $commentId
                );


            /*
            |--------------------------------------------------------------------------
            | Update
            |--------------------------------------------------------------------------
            |
            | CommentService bertanggung jawab
            | mengecek apakah user boleh mengubah
            | comment tersebut.
            |
            */

            $this->commentService->update(
                $commentId,
                $content,
                $userId
            );


            /*
            |--------------------------------------------------------------------------
            | Redirect
            |--------------------------------------------------------------------------
            */

            $this->redirectToArticle(
                $articleId
            );

        } catch (\Throwable $e) {

            $commentId =
                (int) (
                    $_POST['comment_id'] ?? 0
                );


            $this->handleError(
                $e->getMessage(),
                null,
                $commentId
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE COMMENT
    |--------------------------------------------------------------------------
    */

    public function postDelete(): void
    {
        try {

            /*
            |--------------------------------------------------------------------------
            | Current User
            |--------------------------------------------------------------------------
            */

            $userId =
                $this->getCurrentUserId();


            /*
            |--------------------------------------------------------------------------
            | Request
            |--------------------------------------------------------------------------
            */

            $commentId =
                (int) (
                    $_POST['comment_id'] ?? 0
                );


            if ($commentId <= 0) {
                throw new Exception(
                    'Comment not found.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Article
            |--------------------------------------------------------------------------
            */

            $articleId =
                $this->getArticleIdFromComment(
                    $commentId
                );


            /*
            |--------------------------------------------------------------------------
            | Delete
            |--------------------------------------------------------------------------
            */

            $this->commentService->delete(
                $commentId,
                $userId
            );


            /*
            |--------------------------------------------------------------------------
            | Redirect
            |--------------------------------------------------------------------------
            */

            $this->redirectToArticle(
                $articleId
            );

        } catch (\Throwable $e) {

            $commentId =
                (int) (
                    $_POST['comment_id'] ?? 0
                );


            $this->handleError(
                $e->getMessage(),
                null,
                $commentId
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | LIKE COMMENT
    |--------------------------------------------------------------------------
    */

    public function postLike(): void
    {
        try {

            /*
            |--------------------------------------------------------------------------
            | Current User
            |--------------------------------------------------------------------------
            */

            $userId =
                $this->getCurrentUserId();


            /*
            |--------------------------------------------------------------------------
            | Comment
            |--------------------------------------------------------------------------
            */

            $commentId =
                (int) (
                    $_POST['comment_id'] ?? 0
                );


            if ($commentId <= 0) {
                throw new Exception(
                    'Comment not found.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Article
            |--------------------------------------------------------------------------
            */

            $articleId =
                $this->getArticleIdFromComment(
                    $commentId
                );


            /*
            |--------------------------------------------------------------------------
            | Like
            |--------------------------------------------------------------------------
            */

            $this->commentLikeService->like(
                $commentId,
                $userId
            );


            /*
            |--------------------------------------------------------------------------
            | Redirect
            |--------------------------------------------------------------------------
            */

            $this->redirectToArticle(
                $articleId
            );

        } catch (\Throwable $e) {

            $commentId =
                (int) (
                    $_POST['comment_id'] ?? 0
                );


            $this->handleError(
                $e->getMessage(),
                null,
                $commentId
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | UNLIKE COMMENT
    |--------------------------------------------------------------------------
    */

    public function postUnlike(): void
    {
        try {

            /*
            |--------------------------------------------------------------------------
            | Current User
            |--------------------------------------------------------------------------
            */

            $userId =
                $this->getCurrentUserId();


            /*
            |--------------------------------------------------------------------------
            | Comment
            |--------------------------------------------------------------------------
            */

            $commentId =
                (int) (
                    $_POST['comment_id'] ?? 0
                );


            if ($commentId <= 0) {
                throw new Exception(
                    'Comment not found.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Article
            |--------------------------------------------------------------------------
            */

            $articleId =
                $this->getArticleIdFromComment(
                    $commentId
                );


            /*
            |--------------------------------------------------------------------------
            | Unlike
            |--------------------------------------------------------------------------
            */

            $this->commentLikeService->unlike(
                $commentId,
                $userId
            );


            /*
            |--------------------------------------------------------------------------
            | Redirect
            |--------------------------------------------------------------------------
            */

            $this->redirectToArticle(
                $articleId
            );

        } catch (\Throwable $e) {

            $commentId =
                (int) (
                    $_POST['comment_id'] ?? 0
                );


            $this->handleError(
                $e->getMessage(),
                null,
                $commentId
            );
        }
    }
}