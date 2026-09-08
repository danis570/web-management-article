<?php

namespace app\Controller;

use app\App\Database;
use app\Repository\TagRepository;
use app\Service\TagService;

class TagController
{
    private TagService $tagService;

    public function __construct()
    {
        $pdo = Database::getConnection();

        $tagRepository = new TagRepository($pdo);

        $this->tagService = new TagService(
            $tagRepository
        );
    }

    public function search(): void
    {
        header('Content-Type: application/json');

        $keyword = trim($_GET['keyword'] ?? '');

        if ($keyword === '') {
            echo json_encode([]);
            return;
        }

        $tags = $this->tagService->search($keyword);

        echo json_encode($tags);
    }
}