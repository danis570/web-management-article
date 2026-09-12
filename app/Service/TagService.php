<?php

namespace app\Service;

use app\Domain\Tag;
use app\Repository\TagRepository;
use Exception;

class TagService
{
    public function __construct(
        private TagRepository $tagRepository
    ) {
    }

    public function add(Tag $tag): Tag
    {
        $this->validation($tag);

        $tag->name = trim($tag->name);
        $tag->slug = trim($tag->slug);

        // Cek slug sudah digunakan
        if ($this->tagRepository->existsBySlug($tag->slug)) {
            throw new Exception('Slug already exists.');
        }

        return $this->tagRepository->save($tag);
    }

    public function getById(int $id): Tag|false
    {
        if ($id <= 0) {
            return false;
        }

        return $this->tagRepository->findById($id);
    }

    public function getBySlug(string $slug): Tag|false
    {
        $slug = trim($slug);

        if ($slug === '') {
            return false;
        }

        return $this->tagRepository->findBySlug($slug);
    }

    public function search(
        string $keyword,
        int $limit = 10
    ): array {
        $keyword = trim($keyword);

        if ($keyword === '') {
            return [];
        }

        if ($limit <= 0) {
            $limit = 10;
        }

        return $this->tagRepository->search(
            $keyword,
            $limit
        );
    }

    public function getAll(): array
    {
        return $this->tagRepository->getAll();
    }

    public function edit(Tag $tag): Tag
    {
        $this->validation($tag);

        if (!$tag->id || $tag->id <= 0) {
            throw new Exception('Invalid tag ID.');
        }

        $existingTag = $this->tagRepository->findById($tag->id);

        if (!$existingTag) {
            throw new Exception('Tag not found.');
        }

        $tag->name = trim($tag->name);
        $tag->slug = trim($tag->slug);

        // Cek slug digunakan tag lain
        if (
            $this->tagRepository->existsBySlug(
                $tag->slug,
                $tag->id
            )
        ) {
            throw new Exception('Slug already exists.');
        }

        return $this->tagRepository->update($tag);
    }

    public function delete(int $id): void
    {
        if ($id <= 0) {
            throw new Exception('Invalid tag ID.');
        }

        $tag = $this->tagRepository->findById($id);

        if (!$tag) {
            throw new Exception('Tag not found.');
        }

        $this->tagRepository->delete($id);
    }

    private function validation(Tag $tag): void
    {
        if (trim($tag->name) === '') {
            throw new Exception('Tag name cannot be blank.');
        }

        if (trim($tag->slug) === '') {
            throw new Exception('Tag slug cannot be blank.');
        }
    }
}