<?php

namespace app\Domain;

class GalleryImage
{
    public ?int $id = null;

    public int $galleryId;

    public string $image;

    public ?string $caption = null;

    public string $createdAt;
}