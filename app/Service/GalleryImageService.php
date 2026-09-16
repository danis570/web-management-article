<?php

namespace app\Service;

use app\Domain\GalleryImage;
use app\Repository\GalleryImageRepository;
use Exception;

class GalleryImageService
{
    private GalleryImageRepository $galleryImageRepository;

    public function __construct(
        GalleryImageRepository $galleryImageRepository
    ) {
        $this->galleryImageRepository = $galleryImageRepository;
    }

    public function create(
        int $galleryId,
        array $imgFileInfo,
        ?string $caption = null
    ): GalleryImage {
        $this->validation(
            $galleryId,
            $caption
        );

        if (
            !isset($imgFileInfo['error']) ||
            $imgFileInfo['error'] === UPLOAD_ERR_NO_FILE
        ) {
            throw new Exception('Image is required');
        }

        $this->uploadImgValidation(
            $imgFileInfo['name'],
            $imgFileInfo['size']
        );

        $image = $this->moveUploadImg(
            $imgFileInfo['name'],
            $imgFileInfo['tmp_name'],
            $imgFileInfo['error']
        );

        $galleryImage = new GalleryImage();

        $galleryImage->galleryId = $galleryId;
        $galleryImage->image = $image;
        $galleryImage->caption = $caption;

        return $this->galleryImageRepository->save(
            $galleryImage
        );
    }

    public function getById(int $id): GalleryImage
    {
        $galleryImage =
            $this->galleryImageRepository->findById($id);

        if ($galleryImage === false) {
            throw new Exception('Gallery image not found');
        }

        return $galleryImage;
    }

    public function getByGalleryId(int $galleryId): array
    {
        if ($galleryId <= 0) {
            throw new Exception('Gallery ID is invalid');
        }

        return $this->galleryImageRepository
            ->getByGalleryId($galleryId);
    }

    public function updateCaption(
        int $imageId,
        ?string $caption
    ): void {
        $image =
            $this->galleryImageRepository
                ->findById($imageId);

        if (!$image) {
            throw new Exception('Gallery image not found.');
        }

        $image->caption = $caption;

        $this->galleryImageRepository->update($image);
    }

    public function update(
        GalleryImage $galleryImage
    ): GalleryImage {
        $this->validation(
            $galleryImage->galleryId,
            $galleryImage->caption
        );

        return $this->galleryImageRepository
            ->update($galleryImage);
    }


public function getFirstByGalleryId(int $galleryId): GalleryImage|false
{
    return $this->galleryImageRepository
        ->getFirstByGalleryId($galleryId);
}



    public function deleteById(int $id): void
    {
        if ($id <= 0) {
            throw new Exception(
                'Gallery image ID is invalid'
            );
        }

        $this->galleryImageRepository
            ->deleteById($id);
    }

    private function validation(
        int $galleryId,
        ?string $caption
    ): void {
        if ($galleryId <= 0) {
            throw new Exception(
                'Gallery ID is invalid'
            );
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

    private function uploadImgValidation(
        string $imgName,
        int|string $imgSize
    ): void {
        $validImgExtension = [
            'png',
            'webp',
            'jpg',
            'jpeg',
            'svg'
        ];

        $imgExtension = strtolower(
            pathinfo(
                $imgName,
                PATHINFO_EXTENSION
            )
        );

        if (
            !in_array(
                $imgExtension,
                $validImgExtension,
                true
            )
        ) {
            throw new Exception(
                'Img format must png, webp, jpg, jpeg, svg'
            );
        }

        // Gallery kita beri maksimal 5 MB
        if ((int) $imgSize > 5000000) {
            throw new Exception(
                'Max size: 5mb'
            );
        }
    }

    private function moveUploadImg(
        string $imgName,
        string $imgTempName,
        int|string $status
    ): string {
        $path =
            __DIR__ .
            '/../../public/uploads/galleries/';

        if ((int) $status !== UPLOAD_ERR_OK) {
            throw new Exception(
                'Error upload img'
            );
        }

        $extension = strtolower(
            pathinfo(
                $imgName,
                PATHINFO_EXTENSION
            )
        );

        $randomHex = bin2hex(
            random_bytes(16)
        );

        $name =
            $randomHex .
            '.' .
            $extension;

        $fullPath = $path . $name;

        if (!is_dir($path)) {
            if (!mkdir($path, 0755, true)) {
                throw new Exception(
                    'Failed to create upload directory'
                );
            }
        }

        if (
            !move_uploaded_file(
                $imgTempName,
                $fullPath
            )
        ) {
            throw new Exception(
                'Failed to move uploaded image'
            );
        }

        return $name;
    }
}