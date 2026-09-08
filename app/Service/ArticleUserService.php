<?php

namespace app\Service;

use app\Domain\ArticleUser;
use app\Repository\ArticleUserRepository;
use Exception;

class ArticleUserService
{
    private ArticleUserRepository $articleUserRepository;

    public function __construct(
        ArticleUserRepository $articleUserRepository
    ) {
        $this->articleUserRepository = $articleUserRepository;
    }

    function add(int $articleId, int $userId): ArticleUser
    {
        if ($articleId <= 0) {
            throw new Exception('Article ID is invalid.');
        }

        if ($userId <= 0) {
            throw new Exception('User ID is invalid.');
        }

        $articleUser = new ArticleUser();

        $articleUser->articleId = $articleId;
        $articleUser->userId = $userId;

        return $this->articleUserRepository->save($articleUser);
    }

    public function sync(int $articleId, array $userIds): void
    {
        $userIds = array_map('intval', $userIds);

        // Hilangkan ID duplikat
        $userIds = array_unique($userIds);

        // Buang ID tidak valid
        $userIds = array_filter(
            $userIds,
            fn($userId) => $userId > 0
        );

        $this->articleUserRepository->sync(
            $articleId,
            $userIds
        );
    }

    public function getUsersByArticleId(int $articleId): array
    {
        return $this->articleUserRepository->getUsersByArticleId(
            $articleId
        );
    }

    function getByArticleId(int $articleId): array
    {
        return $this->articleUserRepository
            ->getByArticleId($articleId);
    }

    function getByUserId(int $userId): array
    {
        return $this->articleUserRepository
            ->getByUserId($userId);
    }

    function delete(int $articleId, int $userId): void
    {
        $this->articleUserRepository
            ->delete($articleId, $userId);
    }

    function deleteByArticleId(int $articleId): void
    {
        $this->articleUserRepository
            ->deleteByArticleId($articleId);
    }
}