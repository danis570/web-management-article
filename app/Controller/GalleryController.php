<?php

namespace app\Controller;

use app\App\BaseController;
use app\App\Database;
use app\App\View;
use app\Repository\ArticleRepository;
use app\Repository\GalleryImageRepository;
use app\Repository\GalleryRepository;
use app\Repository\ProfileRepository;
use app\Repository\SessionRepository;
use app\Repository\UserRepository;
use app\Service\ArticleService;
use app\Service\GalleryImageService;
use app\Service\GalleryService;
use app\Service\ProfileService;
use app\Service\SessionService;
use app\Service\UserService;
use Exception;
use UserRole;

class GalleryController extends BaseController
{
    private GalleryRepository $galleryRepository;
    private GalleryService $galleryService;

    private GalleryImageRepository $galleryImageRepository;
    private GalleryImageService $galleryImageService;
    private UserService $userService;
    private ArticleService $articleService;


    public function __construct()
    {
        $pdo = Database::getConnection();

        $this->galleryRepository =
            new GalleryRepository($pdo);

        $this->galleryService =
            new GalleryService(
                $this->galleryRepository
            );

        $this->galleryImageRepository =
            new GalleryImageRepository($pdo);

        $this->galleryImageService =
            new GalleryImageService(
                $this->galleryImageRepository
            );

        $profileRepository =
            new ProfileRepository($pdo);

        $profileService =
            new ProfileService(
                $profileRepository
            );

        $artcleRepository = new ArticleRepository($pdo);
        $userRepository = new UserRepository($pdo);
        $this->articleService = new ArticleService($artcleRepository, $userRepository);
        $this->userService = new UserService($userRepository);

        $sessionRepository =
            new SessionRepository($pdo);

        $sessionService =
            new SessionService(
                $sessionRepository
            );


        parent::__construct(
            $sessionService,
            $profileService
        );
    }

    public function index(): void
    {
        $user =
            $this->sessionService->getCurrentUserId();

        $data = [
            'title' => 'Gallery',
            'current' => 'gallery',
            'isLoggedIn' => $user !== null,
        ];

        try {

            $galleries =
                $this->galleryService
                    ->getAll();

            $data['galleries'] = $galleries;

            $data['galleryImages'] = [];

            foreach ($galleries as $gallery) {

                $images =
                    $this->galleryImageService
                        ->getByGalleryId($gallery->id);

                $data['galleryImages'][$gallery->id] =
                    $images[0] ?? null;
            }

        } catch (Exception $e) {

            $data['emptyGallery'] =
                $e->getMessage();
        }

        $layout =
            ($user !== null)
            ? 'User'
            : 'Public';

        View::render(
            $layout,
            '/Gallery/gallery',
            $data
        );
    }

    public function userGallery(array $params): void
    {
        try {
            $username = ltrim(
                trim($params['username'] ?? ''),
                '@'
            );

            if ($username === '') {
                throw new Exception('Username cannot be empty.');
            }

            $userProfile = $this->articleService
                ->getByUsername($username);

            if (!$userProfile) {
                throw new Exception('User not found.');
            }

            $galleries = $this->galleryService
                ->getByUserId($userProfile->id);

            $galleryImages = [];

            foreach ($galleries as $gallery) {
                $images = $this->galleryImageService
                    ->getByGalleryId($gallery->id);

                $galleryImages[$gallery->id] =
                    $images[0] ?? null;
            }

            View::render('Public', '/Gallery/user', [
                'title' => "Gallery $username",
                'user' => $userProfile,
                'username' => $username,
                'galleries' => $galleries,
                'galleryImages' => $galleryImages,
            ]);

        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }


    /*
    |--------------------------------------------------------------------------
    | MY GALLERY
    |--------------------------------------------------------------------------
    |
    | GET /me/gallery
    |
    */

    public function myGallery(): void
    {
        $userId = $this->sessionService->getCurrentUserId();

        if ($userId === null) {
            header('Location: /login');
            exit();
        }

        $data = [
            'title' => 'My Gallery',
            'current' => 'my-gallery',
        ];

        try {
            $data['galleries'] =
                $this->galleryService->getByUserId($userId);
        } catch (Exception $e) {
            $data['emptyGallery'] = $e->getMessage();
        }

        View::render(
            'User',
            '/User/Gallery/me',
            $data
        );
    }


    /*
    |--------------------------------------------------------------------------
    | GALLERY DETAIL
    |--------------------------------------------------------------------------
    |
    | GET /gallery/{slug}
    |
    */

    public function detail(array $params): void
    {
        $slug = trim($params['slug'] ?? '');

        $userId = $this->sessionService->getCurrentUserId();

        $data = [
            'title' => 'Gallery Detail',
            'current' => 'gallery',
            'isLoggedIn' => $userId !== null,
            'currentUserId' => $userId,
        ];

        try {
            $gallery = $this->galleryService->getBySlug($slug);

            $images = $this->galleryImageService
                ->getByGalleryId($gallery->id);

            $profile = $this->profileService
                ->getByUserId($gallery->userId);
            $recommendedGalleries = $this->galleryService->getLatestGalleries($gallery->id, 4);


            $data['gallery'] = $gallery;
            $data['images'] = $images;
            $data['profile'] = $profile;
            $data['recommendedGalleries'] = $recommendedGalleries;
            $gallery = $this->galleryService->getBySlug($slug);


            $owner = $this->userService
                ->getUserById($gallery->userId);

            $data['owner'] = $owner;

        } catch (Exception $e) {
            $data['error'] = $e->getMessage();
        }

        $layout = ($userId !== null)
            ? 'User'
            : 'Public';

        View::render(
            $layout,
            '/Gallery/detail',
            $data
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ADD GALLERY
    |--------------------------------------------------------------------------
    |
    | GET /gallery/add
    |
    */

    public function add(): void
    {
        View::render(
            'User',
            '/User/Gallery/add',
            [
                'title' => 'Add Gallery',
                'current' => 'gallery',
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | POST ADD GALLERY
    |--------------------------------------------------------------------------
    |
    | POST /gallery/add
    |
    */

    public function postAdd(): void
    {
        try {

            $userId =
                $this->sessionService->getCurrentUserId();


            if ($userId === null) {
                throw new Exception(
                    'User not logged in.'
                );
            }

            $caption =
                trim(
                    $_POST['caption'] ?? ''
                );


            $caption =
                $caption !== ''
                ? $caption
                : null;

            $gallery =
                $this->galleryService
                    ->create(
                        $userId,
                        $caption
                    );


            /*
            |--------------------------------------------------------------------------
            | Images
            |--------------------------------------------------------------------------
            */

            $this->uploadImages(
                $gallery->id
            );


            $_SESSION['flash_message'] =
                'Success add new gallery';


            header(
                "Location: /me/gallery"
            );

            exit();

        } catch (Exception $e) {

            $_SESSION['flash_message'] =
                $e->getMessage();


            header(
                'Location: /gallery/add'
            );

            exit();
        }
    }

    public function edit(array $params): void
    {
        try {

            $userId =
                $this->sessionService->getCurrentUserId();


            if ($userId === null) {
                throw new Exception(
                    'User not logged in.'
                );
            }

            $slug =
                trim(
                    $params['slug'] ?? ''
                );


            $gallery =
                $this->galleryService
                    ->getBySlug(
                        $slug
                    );


            if (
                (int) $gallery->userId !==
                (int) $userId
            ) {
                throw new Exception(
                    'You are not allowed to edit this gallery.'
                );
            }

            $images =
                $this->galleryImageService
                    ->getByGalleryId(
                        $gallery->id
                    );


            View::render(
                'User',
                '/user/Gallery/edit',
                [
                    'title' => 'Edit Gallery',
                    'current' => 'gallery',
                    'gallery' => $gallery,
                    'images' => $images,
                ]
            );

        } catch (Exception $e) {

            $_SESSION['flash_message'] =
                $e->getMessage();


            header(
                'Location: /gallery'
            );

            exit();
        }
    }


    /*
    |--------------------------------------------------------------------------
    | POST EDIT GALLERY
    |--------------------------------------------------------------------------
    |
    | POST /gallery/edit/{slug}
    |
    */

    public function postEdit(array $params): void
    {
        try {

            /*
            |--------------------------------------------------------------------------
            | Current User
            |--------------------------------------------------------------------------
            */

            $userId =
                $this->sessionService
                    ->getCurrentUserId();

            if ($userId === null) {
                throw new Exception(
                    'User not logged in.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Gallery
            |--------------------------------------------------------------------------
            */

            $slug =
                trim(
                    $params['slug'] ?? ''
                );

            $gallery =
                $this->galleryService
                    ->getBySlug(
                        $slug
                    );


            /*
            |--------------------------------------------------------------------------
            | Owner Check
            |--------------------------------------------------------------------------
            */

            if (
                (int) $gallery->userId !==
                (int) $userId
            ) {
                throw new Exception(
                    'You are not allowed to edit this gallery.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Gallery Data
            |--------------------------------------------------------------------------
            */

            $caption =
                trim(
                    $_POST['caption'] ?? ''
                );

            $gallery->caption =
                $caption !== ''
                ? $caption
                : null;


            /*
            |--------------------------------------------------------------------------
            | Update Gallery
            |--------------------------------------------------------------------------
            */

            $this->galleryService
                ->update(
                    $gallery
                );


            /*
            |--------------------------------------------------------------------------
            | Update Existing Image Captions
            |--------------------------------------------------------------------------
            */

            $imageCaptions =
                $_POST['image_captions'] ?? [];

            foreach ($imageCaptions as $imageId => $imageCaption) {

                $imageId =
                    (int) $imageId;

                $imageCaption =
                    trim(
                        $imageCaption
                    );

                $this->galleryImageService
                    ->updateCaption(
                        $imageId,
                        $imageCaption !== ''
                        ? $imageCaption
                        : null
                    );
            }

            $deleteImages =
                $_POST['delete_images'] ?? [];

            foreach ($deleteImages as $imageId) {

                $imageId =
                    (int) $imageId;

                $this->galleryImageService
                    ->deleteById(
                        $imageId
                    );
            }


            /*
            |--------------------------------------------------------------------------
            | Upload New Images
            |--------------------------------------------------------------------------
            */

            $this->uploadImages(
                $gallery->id,
                'new_image_captions'
            );


            /*
            |--------------------------------------------------------------------------
            | Success
            |--------------------------------------------------------------------------
            */

            $_SESSION['flash_message'] =
                'Success update gallery';

            header(
                'Location: /me/gallery'
            );

            exit();

        } catch (Exception $e) {

            $_SESSION['flash_message'] =
                $e->getMessage();

            header(
                'Location: /gallery/edit/' .
                urlencode(
                    $params['slug'] ?? ''
                )
            );

            exit();
        }
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE GALLERY
    |--------------------------------------------------------------------------
    |
    | POST /gallery/delete/{id}
    |
    */

    public function delete(array $params): void
    {
        try {


            $userId =
                $this->sessionService->getCurrentUserId();


            if ($userId === null) {
                throw new Exception(
                    'User not logged in.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Gallery ID
            |--------------------------------------------------------------------------
            */

            $id =
                (int) (
                    $params['id'] ?? 0
                );


            /*
            |--------------------------------------------------------------------------
            | Gallery
            |--------------------------------------------------------------------------
            */

            $gallery =
                $this->galleryService
                    ->getById(
                        $id
                    );


            /*
            |--------------------------------------------------------------------------
            | Owner Check
            |--------------------------------------------------------------------------
            */

            if (
                (int) $gallery->userId !==
                (int) $userId
            ) {
                throw new Exception(
                    'You are not allowed to delete this gallery.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Delete
            |--------------------------------------------------------------------------
            */

            $this->galleryService
                ->deleteById(
                    $id
                );


            /*
            |--------------------------------------------------------------------------
            | Success
            |--------------------------------------------------------------------------
            */

            $_SESSION['flash_message'] =
                'Success delete gallery';


            header(
                'Location: /me/gallery'
            );

            exit();

        } catch (Exception $e) {

            $_SESSION['flash_message'] =
                $e->getMessage();


            header(
                'Location: /gallery'
            );

            exit();
        }
    }


    /*
    |--------------------------------------------------------------------------
    | UPLOAD GALLERY IMAGES
    |--------------------------------------------------------------------------
    */

    private function uploadImages(
        int $galleryId,
        string $captionField = 'image_captions'
    ): void {
        if (
            !isset($_FILES['images']['name']) ||
            !is_array($_FILES['images']['name'])
        ) {
            return;
        }

        $captions =
            $_POST[$captionField] ?? [];

        if (!is_array($captions)) {
            $captions = [];
        }

        foreach (
            $_FILES['images']['name']
            as $key => $name
        ) {

            $error =
                $_FILES['images']['error'][$key]
                ?? UPLOAD_ERR_NO_FILE;


            /*
            |--------------------------------------------------------------------------
            | No File
            |--------------------------------------------------------------------------
            */

            if ($error === UPLOAD_ERR_NO_FILE) {
                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | File Info
            |--------------------------------------------------------------------------
            */

            $file = [
                'name' =>
                    $_FILES['images']['name'][$key],

                'type' =>
                    $_FILES['images']['type'][$key],

                'tmp_name' =>
                    $_FILES['images']['tmp_name'][$key],

                'error' =>
                    $error,

                'size' =>
                    $_FILES['images']['size'][$key],
            ];


            /*
            |--------------------------------------------------------------------------
            | Image Caption
            |--------------------------------------------------------------------------
            */

            $caption =
                trim(
                    $captions[$key] ?? ''
                );

            $caption =
                $caption !== ''
                ? $caption
                : null;


            /*
            |--------------------------------------------------------------------------
            | Save Image
            |--------------------------------------------------------------------------
            */

            $this->galleryImageService
                ->create(
                    $galleryId,
                    $file,
                    $caption
                );
        }
    }
}