<?php
$user = $model['user'];
$profile = $model['profile'];

$email = $user->email ?? '';

$username = $email !== ''
    ? explode('@', $email)[0]
    : '';
?>

<section class="hero user-profile-page">
    <div class="container">


        <!-- =====================================================
             PROFILE
        ====================================================== -->

        <div class="user-profile">


            <!-- =====================================================
                 PROFILE IMAGE
            ====================================================== -->

            <div class="user-profile-image">

                <?php if (!empty($profile->img)): ?>

                    <img src="/uploads/users/<?= htmlspecialchars($profile->img) ?>"
                        alt="<?= htmlspecialchars($profile->name) ?>" loading="lazy">

                <?php else: ?>

                    <div class="user-profile-placeholder">
                        <?= strtoupper(
                            substr(
                                $profile->name ?? $username,
                                0,
                                1
                            )
                        ) ?>
                    </div>

                <?php endif; ?>

            </div>


            <!-- =====================================================
                 PROFILE INFORMATION
            ====================================================== -->

            <div class="user-profile-info">


                <!-- =========================
                     NAME
                ========================== -->

                <h1 class="user-profile-name">
                    <?= htmlspecialchars($profile->name) ?>
                </h1>


                <!-- =========================
                     POSITION
                ========================== -->

                <?php if (!empty($profile->position)): ?>

                    <p class="user-profile-position">
                        <?= htmlspecialchars($profile->position) ?>
                    </p>

                <?php endif; ?>


                <!-- =========================
                     PERIOD
                ========================== -->

                <?php if (!empty($profile->period)): ?>

                    <p class="user-profile-period">
                        <?= htmlspecialchars($profile->period) ?>
                    </p>

                <?php endif; ?>


                <!-- =========================
                     USERNAME
                ========================== -->

                <?php if ($username !== ''): ?>

                    <p class="user-profile-username">
                        @<?= htmlspecialchars($username) ?>
                    </p>

                <?php endif; ?>

            </div>

        </div>



        <!-- =====================================================
             LATEST POSTS
        ====================================================== -->

        <div class="user-profile-posts">


            <!-- =========================
                 SECTION HEADER
            ========================== -->

            <div class="user-posts-header">

                <h2 class="user-posts-title">
                    Latest Posts
                </h2>

            </div>


            <!-- =========================
                 POSTS GRID
            ========================== -->



            <?php if (!empty($model['latestPosts'])): ?>

                <div class="user-posts-grid" id="user-posts-list">

                    <?php foreach ($model['latestPosts'] as $post): ?>

                        <?php
                        $type = $post['type'];
                        $data = $post['data'];
                        ?>

                        <?php if ($type === 'article'): ?>

                            <a href="/article/<?= urlencode($data['slug']) ?>" class="user-post-link">

                                <article class="neo-card user-post-card" data-post-type="article">

                                    <!-- TYPE BADGE -->
                                    <div class="user-post-type">
                                        Article
                                    </div>

                                    <!-- PREVIEW IMAGE -->
                                    <?php if (!empty($data['image'])): ?>

                                        <div class="user-post-image">

                                            <img src="/uploads/articles/<?= htmlspecialchars($data['image']) ?>"
                                                alt="<?= htmlspecialchars($data['title']) ?>" loading="lazy">

                                        </div>

                                    <?php else: ?>

                                        <div class="user-post-image user-post-image-placeholder">
                                            <span>No Image</span>
                                        </div>

                                    <?php endif; ?>

                                    <!-- CONTENT -->
                                    <div class="user-post-content">

                                        <h3 class="user-post-title">
                                            <?= htmlspecialchars($data['title']) ?>
                                        </h3>

                                        <p class="user-post-date">
                                            <?= htmlspecialchars($data['created_at']) ?>
                                        </p>

                                    </div>

                                </article>

                            </a>


                        <?php elseif ($type === 'gallery'): ?>

                            <?php
                            $previewImage = $model['gallery_images'][$data->id] ?? null;
                            ?>

                            <a href="/gallery/<?= urlencode($data->slug) ?>" class="user-post-link">

                                <article class="neo-card user-post-card" data-post-type="gallery">

                                    <!-- TYPE BADGE -->
                                    <div class="user-post-type">
                                        Gallery
                                    </div>

                                    <!-- PREVIEW IMAGE -->
                                    <?php if ($previewImage !== null): ?>

                                        <div class="user-post-image">

                                            <img src="/uploads/galleries/<?= htmlspecialchars($previewImage->image) ?>"
                                                alt="<?= htmlspecialchars($data->caption ?? 'Gallery') ?>" loading="lazy">

                                        </div>

                                    <?php else: ?>

                                        <div class="user-post-image user-post-image-placeholder">
                                            <span>No Image</span>
                                        </div>

                                    <?php endif; ?>

                                    <!-- CONTENT -->
                                    <div class="user-post-content">

                                        <h3 class="user-post-title">
                                            <?= htmlspecialchars(
                                                $data->caption ?? 'Gallery'
                                            ) ?>
                                        </h3>

                                        <p class="user-post-date">
                                            <?= htmlspecialchars($data->createdAt) ?>
                                        </p>

                                    </div>

                                </article>

                            </a>

                        <?php endif; ?>

                    <?php endforeach; ?>

                </div>

            <?php else: ?>

                <!-- EMPTY STATE -->

                <div class="user-posts-empty">

                    <div class="neo-card empty-state">

                        <div class="empty-state-icon">
                            📝
                        </div>

                        <p>
                            Belum ada postingan.
                        </p>

                    </div>

                </div>

            <?php endif; ?>





        </div>

    </div>
</section>


<style>
    /* =========================================================
   PROFILE PAGE WRAPPER
========================================================= */

    .user-profile-page {

        padding: 3.5rem 0;
    }


    /* =========================================================
   PROFILE
========================================================= */

    .user-profile {

        display: grid;

        grid-template-columns: 140px 1fr;

        align-items: start;

        gap: 1.5rem;

        padding: 1.5rem;

        margin-top: 60px;

        background: #ffffff;

        border: var(--border-width, 3px) solid var(--dark);

        border-radius: 16px;

        box-shadow: var(--shadow-offset, 5px) var(--shadow-offset, 5px) 0 var(--dark);
    }


    /* =========================================================
   PROFILE IMAGE
========================================================= */

    .user-profile-image {

        width: 140px;

        height: 140px;

        flex-shrink: 0;

        overflow: hidden;

        background: #f3f4f6;

        border: var(--border-width, 3px) solid var(--dark);

        border-radius: 16px;
    }


    .user-profile-image img {

        display: block;

        width: 100%;

        height: 100%;

        object-fit: cover;

        object-position: center;
    }


    /* =========================================================
   PROFILE PLACEHOLDER
========================================================= */

    .user-profile-placeholder {

        display: flex;

        align-items: center;

        justify-content: center;

        width: 100%;

        height: 100%;

        background: var(--yellow);

        color: var(--dark);

        font-size: 3rem;

        font-weight: 900;
    }


    /* =========================================================
   PROFILE INFO
========================================================= */

    .user-profile-info {
        min-width: 0;
        max-width: 100%;
        /* HAPUS display: flex */
    }

    .user-profile-name {
        margin: 0 0 0.5rem;
        /* jarak ke bawah */
        font-size: 1.75rem;
        line-height: 1.15;
        font-weight: 900;
        color: var(--dark);
        word-break: break-word;
    }

    .user-profile-position {
        margin: 0 0 0.25rem;
        font-size: 1rem;
        line-height: 1.3;
        font-weight: 700;
        color: var(--dark);
        word-break: break-word;
    }

    .user-profile-period {
        margin: 0 0 0.75rem;
        font-size: 0.9rem;
        line-height: 1.3;
        font-weight: 600;
        color: var(--dark);
        opacity: 0.75;
        word-break: break-word;
    }

    .hero p {
        margin-bottom: 0;
    }

    .user-profile-username {
        display: inline-block;
        width: fit-content;
        max-width: 100%;
        margin: 0;
        padding: 0.25rem 0.625rem;
        background: var(--dark);
        color: #ffffff;
        border: var(--border-width, 3px) solid var(--dark);
        font-size: 0.8rem;
        font-weight: 800;
        line-height: 1;
        word-break: break-all;
        overflow: hidden;
        text-overflow: ellipsis;
    }


    /* =========================================================
   LATEST POSTS
========================================================= */

    .user-profile-posts {

        margin-top: 3.5rem;
    }


    .user-posts-header {

        margin-bottom: 1.5rem;
    }


    .user-posts-title {

        margin: 0;

        font-size: 1.8rem;

        font-weight: 900;

        color: var(--dark);

        line-height: 1.2;
    }


    /* =========================================================
   POSTS GRID
========================================================= */

    .user-posts-grid {

        display: grid;

        /*
    Auto-fill: kartu otomatis mengisi kolom
    sesuai lebar container.
    minmax(220px, 1fr): minimal 220px, maksimal 1fr.
    max 4 kolom karena container dibatasi.
    */

        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));

        gap: 1.5rem;

        align-items: start;

        width: 100%;
    }


    /* =========================================================
   POST CARD
========================================================= */

    .neo-card.user-post-card {

        position: relative;

        display: flex;

        flex-direction: column;

        padding: 0;

        overflow: hidden;

        background-color: #ffffff;

        text-decoration: none;

        color: inherit;

        transition:
            transform 0.3s ease,
            box-shadow 0.3s ease;
    }


    .neo-card.user-post-card:hover {

        transform: translateY(-4px);

        box-shadow: 6px 6px 0px var(--dark);
    }


    /* =========================================================
   POST TYPE BADGE — OVERLAY DI ATAS IMAGE
========================================================= */

    .user-post-card .user-post-type {

        position: absolute;

        top: 0;

        left: 0;

        z-index: 2;

        width: fit-content;

        padding: 0.375rem 0.75rem;

        background: var(--yellow);

        color: var(--dark);

        border-right: var(--border-width, 3px) solid var(--dark);

        border-bottom: var(--border-width, 3px) solid var(--dark);

        font-size: 0.8rem;

        line-height: 1;

        font-weight: 900;

        text-transform: uppercase;

        letter-spacing: 0.05em;
    }

    /* =========================================================
   POST IMAGE
========================================================= */

    .user-post-card .user-post-image {

        width: 100%;

        /*
    Pakai aspect-ratio biar tinggi image konsisten,
    tidak membuat card tinggi berbeda-beda.
    */

        aspect-ratio: 16 / 10;

        overflow: hidden;

        flex-shrink: 0;

        margin: 0;

        padding: 0;

        background: #f3f4f6;

        border-bottom: var(--border-width, 3px) solid var(--dark);
    }


    .user-post-card .user-post-image img {

        display: block;

        width: 100%;

        height: 100%;

        margin: 0;

        padding: 0;

        border: 0;

        object-fit: cover;

        object-position: center;

        transition: transform 0.4s ease;
    }


    .neo-card.user-post-card:hover .user-post-image img {

        transform: scale(1.05);
    }


    /* =========================================================
   POST IMAGE PLACEHOLDER
========================================================= */

    .user-post-card .user-post-image-placeholder {

        display: flex;

        align-items: center;

        justify-content: center;

        background:
            repeating-linear-gradient(45deg,
                #f3f4f6,
                #f3f4f6 10px,
                #e5e7eb 10px,
                #e5e7eb 20px);

        color: #6b7280;

        font-weight: 700;

        font-size: 0.85rem;

        letter-spacing: 0.05em;

        text-transform: uppercase;
    }


    /* =========================================================
   POST CONTENT
========================================================= */

    .user-post-card .user-post-content {

        padding: 1.25rem;

        display: flex;

        flex-direction: column;

        gap: 0.75rem;

        /*
    Biar tinggi content konsisten.
    */

        flex: 1;
    }


    /* =========================================================
   POST TITLE
========================================================= */

    .user-post-card .user-post-title {

        margin: 0;

        font-size: 1.1rem;

        line-height: 1.3;

        font-weight: 800;

        color: var(--dark);

        display: -webkit-box;

        -webkit-line-clamp: 2;

        -webkit-box-orient: vertical;

        overflow: hidden;

        word-break: break-word;
    }


    /* =========================================================
   POST DATE
========================================================= */

    .user-post-card .user-post-date {

        margin: 0;

        font-size: 0.85rem;

        line-height: 1.4;

        font-weight: 600;

        color: var(--dark);

        opacity: 0.7;
    }


    /* =========================================================
   EMPTY STATE
========================================================= */

    .user-posts-empty {

        width: 100%;
    }


    .empty-state {

        padding: 2.5rem 2rem;

        text-align: center;

        font-weight: 800;
    }


    .empty-state .empty-state-icon {

        font-size: 3rem;

        margin-bottom: 1rem;

        line-height: 1;
    }


    .empty-state p {

        margin: 0;
    }


    /* =========================================================
   RESPONSIVE — 1000px
========================================================= */

    @media (max-width: 1000px) {

        .user-profile {

            grid-template-columns: 120px 1fr;

            gap: 1.25rem;
        }


        .user-profile-image {

            width: 120px;

            height: 120px;
        }


        .user-profile-name {

            font-size: 1.5rem;
        }


        .user-posts-grid {

            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        }
    }


    /* =========================================================
   RESPONSIVE — 768px
========================================================= */

    @media (max-width: 768px) {

        .user-profile {

            grid-template-columns: 1fr;

            padding: 1.5rem;

            text-align: center;

            justify-items: center;

            max-width: 100%;
        }


        .user-profile-image {

            width: 120px;

            height: 120px;
        }


        .user-profile-info {

            align-items: center;
        }


        .user-profile-name {

            font-size: 1.5rem;
        }


        .user-profile-position {

            font-size: 0.95rem;
        }


        .user-posts-grid {

            grid-template-columns: 1fr;

            gap: 1rem;
        }


        .user-profile-posts {

            margin-top: 2.5rem;
        }
    }


    /* =========================================================
   RESPONSIVE — 480px
========================================================= */

    @media (max-width: 480px) {

        .user-profile-page {

            padding: 2.5rem 0;
        }


        .user-profile {

            padding: 1.25rem;
        }


        .user-profile-image {

            width: 100px;

            height: 100px;
        }


        .user-profile-name {

            font-size: 1.35rem;
        }


        .user-profile-position {

            font-size: 0.9rem;
        }


        .user-profile-period {

            font-size: 0.8rem;
        }


        .user-profile-username {

            font-size: 0.75rem;
        }


        .user-posts-title {

            font-size: 1.5rem;
        }


        .user-post-card .user-post-content {

            padding: 1rem;
        }


        .user-post-card .user-post-title {

            font-size: 1rem;
        }


        .user-post-card .user-post-date {

            font-size: 0.8rem;
        }
    }
</style>