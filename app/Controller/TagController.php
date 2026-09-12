<?php

namespace app\Controller;

use app\App\Database;
use app\App\View;
use app\Domain\Tag;
use app\Repository\TagRepository;
use app\Service\TagService;
use Exception;

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

    public function index(): void
    {
        try {
            $tags = $this->tagService->getAll();

            View::render('Admin', '/Admin/Tag/tag', [
                'title' => 'Manage Tags',
                'current' => 'tag',
                'tags' => $tags
            ]);
        } catch (Exception $e) {
            View::render('Admin', '/Admin/Tag/tag', [
                'title' => 'Manage Tags',
                'current' => 'tag',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function postAdd(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        try {
            $tag = new Tag();

            $tag->name = $_POST['name'] ?? '';
            $tag->slug = $_POST['slug'] ?? '';

            $this->tagService->add($tag);

            header('Location: /tag');
            exit();
        } catch (Exception $e) {
            $_SESSION['flash_message'] = $e->getMessage();
            header('Location: /tag');
            exit();
        }
    }


    public function postEdit(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        try {
            $tag = new Tag();

            $tag->id = (int) ($_POST['id'] ?? 0);
            $tag->name = $_POST['name'] ?? '';
            $tag->slug = $_POST['slug'] ?? '';

            $this->tagService->edit($tag);

            header('Location: /tag');
            exit();
        } catch (Exception $e) {
            $_SESSION['flash_message'] = $e->getMessage();
            header('Location: /tag');
            exit();
        }
    }

    public function delete(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        try {
            $id = (int) ($_POST['id'] ?? 0);

            $this->tagService->delete($id);

            $_SESSION['flash_message'] = 'success delete tag';
            header('Location: /tag');
            exit();
        } catch (Exception $e) {
            header('Location: /tag?error=' . urlencode($e->getMessage()));
            exit();
        }
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