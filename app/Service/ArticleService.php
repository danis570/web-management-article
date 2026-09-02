<?php

namespace app\Service;

use app\Domain\Article;
use app\Model\ArticleAddRequest;
use app\Model\ArticleAddResponse;
use app\Repository\ArticleRepository;
use Exception;

class ArticleService
{
    private ArticleRepository $articleRepository;

    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    function add(ArticleAddRequest $request): ArticleAddResponse
    {
        $this->addValidation($request);

        $article = new Article();
        $article->title = $request->title;
        $article->content = $request->content;
        $article->userId = $request->userId;

        $result = $this->articleRepository->save($article);
        $response = new ArticleAddResponse();
        $response->article = $result;
        return $response;
    }

    function getByUserId(int $userId): array
    {
        $result = $this->articleRepository->getByUserId($userId);

        if ($result) {
            return $result;
        } else {
            throw new Exception("You don't have any articles yet.");
        }
    }

    function getAll(): array
    {
        $result = $this->articleRepository->getAll();

        if ($result) {
            return $result;
        } else {
            throw new Exception("No Aarticles Yet.");
        }
    }

    private function addValidation(ArticleAddRequest $request)
    {
        if (trim($request->title) == '' || trim($request->title) == '') {
            throw new Exception('Title or content cannot blank');
        }
    }
}