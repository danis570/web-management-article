<?php

namespace app\Service;

use app\Domain\CommentLike;
use app\Repository\CommentLikeRepository;
use PDO;
use Exception;

class CommentLikeService
{
    private PDO $pdo;
    private CommentLikeRepository $commentLikeRepository;
    private CommentService $commentService;

    public function __construct(
        PDO $pdo,
        CommentLikeRepository $commentLikeRepository,
        CommentService $commentService
    ) {
        $this->pdo = $pdo;
        $this->commentLikeRepository = $commentLikeRepository;
        $this->commentService = $commentService;
    }

    public function like(
        int $commentId,
        int $userId
    ): bool {
        $comment = $this->commentService->getById($commentId);

        if (!$comment) {
            throw new Exception('Comment not found');
        }

        $existingLike = $this->commentLikeRepository
            ->findByCommentAndUser($commentId, $userId);

        if ($existingLike) {
            throw new Exception('You already liked this comment');
        }

        $this->pdo->beginTransaction();

        try {
            $commentLike = new CommentLike();

            $commentLike->commentId = $commentId;
            $commentLike->userId = $userId;

            $this->commentLikeRepository->save($commentLike);

            $this->commentService->incrementLikeCount($commentId);

            $this->pdo->commit();

            return true;

        } catch (\Throwable $e) {

            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }

            throw $e;
        }
    }

    public function unlike(
        int $commentId,
        int $userId
    ): bool {
        $existingLike = $this->commentLikeRepository
            ->findByCommentAndUser($commentId, $userId);

        if (!$existingLike) {
            throw new Exception('You have not liked this comment');
        }

        $this->pdo->beginTransaction();

        try {
            $this->commentLikeRepository->deleteByCommentAndUser(
                $commentId,
                $userId
            );

            $this->commentService->decrementLikeCount($commentId);

            $this->pdo->commit();

            return true;

        } catch (\Throwable $e) {

            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }

            throw $e;
        }
    }

    public function isLiked(
        int $commentId,
        int $userId
    ): bool {
        return $this->commentLikeRepository
            ->findByCommentAndUser($commentId, $userId) !== false;
    }



}