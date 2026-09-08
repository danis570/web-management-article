<?php

namespace app\Service;

use app\Domain\ArticleImage;
use app\Repository\ArticleImageRepository;
use Exception;

class ArticleImageService
{
    private ArticleImageRepository $articleImageRepository;

    public function __construct(
        ArticleImageRepository $articleImageRepository
    ) {
        $this->articleImageRepository = $articleImageRepository;
    }

    function add(
        int $articleId,
        array $file,
        ?string $caption = null
    ): ArticleImage {

        if ($articleId <= 0) {
            throw new Exception('Article ID is invalid.');
        }

        // Cek error upload
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('Image upload failed.');
        }

        // Maksimal 5 MB
        $maxSize = 5 * 1024 * 1024;

        if ($file['size'] > $maxSize) {
            throw new Exception('Image size cannot exceed 5 MB.');
        }

        // Cek MIME type sebenarnya
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($file['tmp_name']);

        $allowedMimeTypes = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
        ];

        if (!isset($allowedMimeTypes[$mimeType])) {
            throw new Exception(
                'Only JPG, PNG, and WEBP images are allowed.'
            );
        }

        // Nama file baru
        $extension = $allowedMimeTypes[$mimeType];

        $fileName = bin2hex(random_bytes(16))
            . '.' . $extension;

        // Folder upload
        $uploadDirectory = __DIR__ . '/../../public/uploads/articles/';

        // Buat folder jika belum ada
        if (!is_dir($uploadDirectory)) {
            mkdir($uploadDirectory, 0755, true);
        }

        $destination = $uploadDirectory . $fileName;

        // Pindahkan file
        if (
            !move_uploaded_file(
                $file['tmp_name'],
                $destination
            )
        ) {
            throw new Exception('Failed to save image.');
        }

        try {

            // Simpan metadata ke database
            $articleImage = new ArticleImage();

            $articleImage->articleId = $articleId;
            $articleImage->image = $fileName;
            $articleImage->caption = $caption;

            return $this->articleImageRepository
                ->save($articleImage);

        } catch (Exception $e) {

            // Kalau DB gagal, hapus file yang sudah terupload
            if (file_exists($destination)) {
                unlink($destination);
            }

            throw $e;
        }
    }

    function getByArticleId(int $articleId): array
    {
        return $this->articleImageRepository
            ->getByArticleId($articleId);
    }

    public function updateCaption(
        int $id,
        string $caption
    ): void {

        $caption = trim($caption);

        $this->articleImageRepository
            ->updateCaption(
                $id,
                $caption
            );
    }


    function deleteById(int $id): void
    {
        $articleImage = $this->articleImageRepository
            ->findById($id);

        if (!$articleImage) {
            throw new Exception('Image not found.');
        }

        $uploadDirectory =
            __DIR__ . '/../../public/uploads/articles/';

        $filePath =
            $uploadDirectory . $articleImage->image;

        // Hapus file fisik
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        // Hapus data database
        $this->articleImageRepository
            ->deleteById($id);
    }



}