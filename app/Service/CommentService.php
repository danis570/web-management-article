<?php

namespace app\Service;

use app\Domain\Comment;
use app\Repository\CommentRepository;
use Exception;

class CommentService
{
    private CommentRepository $commentRepository;

    public function __construct(CommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    public function create(
        string $content,
        int $articleId,
        int $userId,
        ?int $parentId = null
    ): Comment {
        $content = trim($content);

        if ($content === '') {
            throw new Exception('Comment cannot be blank');
        }

        $comment = new Comment();

        $comment->content = $content;
        $comment->articleId = $articleId;
        $comment->userId = $userId;
        $comment->parentId = $parentId;

        return $this->commentRepository->save($comment);
    }

    public function getById(int $id): Comment|false
    {
        return $this->commentRepository->findById($id);
    }

    public function getByArticleId(
        int $articleId,
        int $userId = 0
    ): array {
        return $this->commentRepository->getByArticleId(
            $articleId,
            $userId
        );
    }

    public function getByUserId(int $userId): array
    {
        return $this->commentRepository->getByUserId($userId);
    }

    public function update(
        int $commentId,
        string $content,
        int $userId
    ): bool {
        $comment = $this->commentRepository->findById($commentId);

        if (!$comment) {
            throw new Exception('Comment not found');
        }

        if ($comment->userId !== $userId) {
            throw new Exception('You are not allowed to edit this comment');
        }

        $content = trim($content);

        if ($content === '') {
            throw new Exception('Comment cannot be blank');
        }

        $comment->content = $content;

        return $this->commentRepository->update($comment);
    }

    public function incrementLikeCount(int $commentId): bool
    {
        return $this->commentRepository->incrementLikeCount($commentId);
    }

    public function decrementLikeCount(int $commentId): bool
    {
        return $this->commentRepository->decrementLikeCount($commentId);
    }

    public function delete(
        int $commentId,
        int $userId
    ): bool {
        $comment = $this->commentRepository->findById($commentId);

        if (!$comment) {
            throw new Exception('Comment not found');
        }

        if ($comment->userId !== $userId) {
            throw new Exception('You are not allowed to delete this comment');
        }

        return $this->commentRepository->delete($commentId);
    }
}