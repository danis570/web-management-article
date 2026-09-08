<?php

namespace app\Service;

use app\Domain\ArticleTag;
use app\Repository\ArticleTagRepository;
use Exception;

class ArticleTagService
{
    public function __construct(
        private ArticleTagRepository $articleTagRepository
    ) {
    }

    public function add(
        int $articleId,
        int $tagId
    ): ArticleTag {
        if ($articleId <= 0) {
            throw new Exception('Invalid article ID.');
        }

        if ($tagId <= 0) {
            throw new Exception('Invalid tag ID.');
        }

        $articleTag = new ArticleTag();

        $articleTag->articleId = $articleId;
        $articleTag->tagId = $tagId;

        return $this->articleTagRepository->save(
            $articleTag
        );
    }

    public function getByArticleId(
        int $articleId
    ): array {
        if ($articleId <= 0) {
            return [];
        }

        return $this->articleTagRepository
            ->getByArticleId($articleId);
    }

    public function getByTagId(
        int $tagId
    ): array {
        if ($tagId <= 0) {
            return [];
        }

        return $this->articleTagRepository
            ->getByTagId($tagId);
    }

    public function delete(
        int $articleId,
        int $tagId
    ): void {
        if ($articleId <= 0) {
            throw new Exception('Invalid article ID.');
        }

        if ($tagId <= 0) {
            throw new Exception('Invalid tag ID.');
        }

        $this->articleTagRepository->delete(
            $articleId,
            $tagId
        );
    }

    public function deleteByArticleId(
        int $articleId
    ): void {
        if ($articleId <= 0) {
            throw new Exception('Invalid article ID.');
        }

        $this->articleTagRepository
            ->deleteByArticleId($articleId);
    }

    public function sync(
        int $articleId,
        array $tagIds
    ): void {
        if ($articleId <= 0) {
            throw new Exception('Invalid article ID.');
        }

        $tagIds = array_map('intval', $tagIds);

        $tagIds = array_filter(
            $tagIds,
            fn ($tagId) => $tagId > 0
        );

        $tagIds = array_unique($tagIds);

        $this->articleTagRepository->sync(
            $articleId,
            $tagIds
        );
    }
}