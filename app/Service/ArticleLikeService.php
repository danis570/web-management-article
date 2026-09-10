<?php

namespace app\Service;

use app\Domain\ArticleLike;
use app\Repository\ArticleLikeRepository;

class ArticleLikeService
{
    private ArticleLikeRepository $articleLikeRepository;

    public function __construct(ArticleLikeRepository $articleLikeRepository)
    {
        $this->articleLikeRepository = $articleLikeRepository;
    }

    public function like(
        int $articleId,
        string $visitorId,
        ?int $userId = null
    ): bool {

        $existingLike = $this->articleLikeRepository
            ->findByArticleAndVisitor(
                $articleId,
                $visitorId
            );

        if ($existingLike) {
            return false;
        }

        $articleLike = new ArticleLike();

        $articleLike->articleId = $articleId;
        $articleLike->visitorId = $visitorId;
        $articleLike->userId = $userId;

        $this->articleLikeRepository->save($articleLike);

        return true;
    }

    public function unlike(
        int $articleId,
        string $visitorId
    ): bool {

        $existingLike = $this->articleLikeRepository
            ->findByArticleAndVisitor(
                $articleId,
                $visitorId
            );

        if (!$existingLike) {
            return false;
        }

        $this->articleLikeRepository
            ->deleteByArticleAndVisitor(
                $articleId,
                $visitorId
            );

        return true;
    }

    public function isLiked(
        int $articleId,
        string $visitorId
    ): bool {

        return $this->articleLikeRepository
            ->findByArticleAndVisitor(
                $articleId,
                $visitorId
            ) !== false;
    }
}