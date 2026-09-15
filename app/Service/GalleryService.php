<?php

namespace app\Service;

use app\Domain\Gallery;
use app\Repository\GalleryRepository;
use Exception;

class GalleryService
{
    private GalleryRepository $galleryRepository;

    public function __construct(
        GalleryRepository $galleryRepository
    ) {
        $this->galleryRepository = $galleryRepository;
    }

    public function create(
        int $userId,
        ?string $caption
    ): Gallery {
        $this->validation(
            $userId,
            $caption
        );

        $gallery = new Gallery();

        $gallery->userId = $userId;
        $gallery->caption = $caption;
        $gallery->slug = uniqid();

        return $this->galleryRepository->save($gallery);
    }

    public function getById(int $id): Gallery
    {
        $gallery = $this->galleryRepository->findById($id);

        if ($gallery === false) {
            throw new Exception('Gallery not found');
        }

        return $gallery;
    }

    public function getBySlug(string $slug): Gallery
    {
        $gallery = $this->galleryRepository->getBySlug($slug);

        if ($gallery === false) {
            throw new Exception('Gallery not found');
        }

        return $gallery;
    }

    public function getAll(): array
    {
        return $this->galleryRepository->getAll();
    }

    public function getByUserId(int $userId): array
    {
        if ($userId <= 0) {
            throw new Exception('User ID is invalid');
        }

        return $this->galleryRepository->getByUserId($userId);
    }

    public function update(Gallery $gallery): Gallery
    {
        $this->validation(
            $gallery->userId,
            $gallery->caption,
            $gallery->slug
        );

        return $this->galleryRepository->update($gallery);
    }

    public function deleteById(int $id): void
    {
        if ($id <= 0) {
            throw new Exception('Gallery ID is invalid');
        }

        $this->galleryRepository->deleteById($id);
    }

    private function validation(
        int $userId,
        ?string $caption
    ): void {
        if ($userId <= 0) {
            throw new Exception('User ID is invalid');
        }


        if (
            $caption !== null &&
            strlen(trim($caption)) > 500
        ) {
            throw new Exception(
                'Caption cannot exceed 500 characters'
            );
        }
    }
}