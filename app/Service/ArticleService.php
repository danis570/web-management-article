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
        $article->slug = $this->createSlug($request->title);
        $article->content = $request->content;
        $article->status = 'draft';

        $result = $this->articleRepository->save($article);

        $response = new ArticleAddResponse();
        $response->article = $result;

        return $response;
    }

    function edit(ArticleEditRequest $request): ArticleEditResponse
    {
        $this->editValidation($request);

        $article = $this->articleRepository->findById($request->id);

        if (!$article) {
            throw new Exception("Article not found.");
        }

        $article->title = $request->title;
        $article->content = $request->content;

        $result = $this->articleRepository->update($article);

        $response = new ArticleEditResponse();
        $response->article = $result;

        return $response;
    }

    function deleteById(int $id): void
    {
        $this->articleRepository->deleteById($id);
    }

    function getByUserId(int $userId): array
    {
        $result = $this->articleRepository->getByUserId($userId);

        if ($result) {
            return $result;
        }

        throw new Exception("You don't have any articles yet.");
    }

    function getById(int $id): array
    {
        $result = $this->articleRepository->getById($id);

        if ($result) {
            return $result;
        }

        throw new Exception("Article not found.");
    }

    function getAll(): array
    {
        $result = $this->articleRepository->getAll();

        if ($result) {
            return $result;
        }

        throw new Exception("No articles yet.");
    }

    function getAndUser(): array
    {
        $result = $this->articleRepository->getAndUser();

        if (!$result) {
            throw new Exception("No articles yet.");
        }

        foreach ($result as &$article) {
            if (strlen($article['content']) > 25) {
                $article['content'] = substr(
                    $article['content'],
                    0,
                    25
                ) . '.....';
            }
        }

        return $result;
    }

    function incrementViewCount(int $id): void
    {
        $this->articleRepository->incrementViewCount($id);
    }

    private function createSlug(string $title): string
    {
        $slug = strtolower(trim($title));
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
        $slug = trim($slug, '-');

        return $slug;
    }

    private function addValidation(ArticleAddRequest $request): void
    {
        if (
            trim($request->title) === '' ||
            trim($request->content) === ''
        ) {
            throw new Exception('Title or content cannot be blank');
        }
    }

    private function editValidation(ArticleEditRequest $request): void
    {
        if (trim($request->title) === '') {
            throw new Exception('Title cannot be blank');
        }
        if (trim($request->content) === '') {
            throw new Exception('Content cannot be blank');
        }
    }
}