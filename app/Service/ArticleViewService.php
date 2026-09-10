<?php

namespace app\Service;

use app\Domain\ArticleView;
use app\Repository\ArticleViewRepository;

class ArticleViewService
{
    private ArticleViewRepository $articleViewRepository;

    public function __construct(ArticleViewRepository $articleViewRepository)
    {
        $this->articleViewRepository = $articleViewRepository;
    }

    public function add(
        int $articleId,
        string $visitorId,
        ?int $userId = null
    ): bool {

        $existingView = $this->articleViewRepository
            ->findByArticleAndVisitor(
                $articleId,
                $visitorId
            );

        if ($existingView) {
            return false;
        }

        $articleView = new ArticleView();

        $articleView->articleId = $articleId;
        $articleView->visitorId = $visitorId;
        $articleView->userId = $userId;

        $this->articleViewRepository->save($articleView);

        return true;
    }
}