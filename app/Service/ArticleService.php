<?php

namespace app\Service;

use app\Domain\Article;
use app\Model\ArticleAddRequest;
use app\Model\ArticleAddResponse;
use app\Model\ArticleEditRequest;
use app\Model\ArticleEditResponse;
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

    function edit(ArticleEditRequest $request): ArticleEditResponse
    {
        $this->editValidation($request);

        $article = new Article();
        $article->id = $request->id;

        $result = $this->articleRepository->findById($article->id);
        $article->title = $result->title;
        $article->userId = $result->userId;
        $article->content = $request->content;

        $updateResponse = $this->articleRepository->updateContent($article);
        $response = new ArticleEditResponse();
        $response->article = $updateResponse;
        return $response;
    }

    function deleteById(int $id)
    {
        $this->articleRepository->deleteById($id);
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

    function getById(int $id): array
    {
        $result = $this->articleRepository->getById($id);

        if ($result) {
            return $result;
        } else {
            throw new Exception("Not articles in current id.");
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

    function getAndUser(): array
    {
        return $this->articleRepository->getAndUser();
    }

    private function addValidation(ArticleAddRequest $request)
    {
        if (trim($request->title) == '' || trim($request->content) == '') {
            throw new Exception('Title or content cannot blank');
        }
    }

    private function editValidation(ArticleEditRequest $request)
    {
        if (trim($request->content) == '') {
            throw new Exception('Content cannot blank');
        }
    }
}