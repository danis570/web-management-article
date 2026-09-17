<!-- Gallery Detail -->
<section class="hero gallery-detail">
    <div class="container">

        <div class="gallery-detail-wrapper">

            <!-- Breadcrumb -->
            <nav class="breadcrumb" aria-label="Breadcrumb">
                <a href="/">Home</a>
                <span>/</span>
                <a href="/gallery">Gallery</a>
            </nav>


            <!-- Gallery -->
            <article class="neo-card gallery-detail-card">

                <!-- Image Slider -->
                <?php if (!empty($model['images'])): ?>

                    <div class="gallery-detail-slider">

                        <div class="gallery-slides">

                            <?php foreach ($model['images'] as $index => $image): ?>

                                <div class="gallery-slide <?= $index === 0 ? 'active' : '' ?>">

                                    <div class="gallery-slide-image">
                                        <img src="/uploads/galleries/<?= htmlspecialchars($image->image) ?>" alt="<?= htmlspecialchars(
                                              $image->caption
                                              ?: ($model['gallery']->caption ?? 'Gallery')
                                          ) ?>">
                                    </div>

                                    <?php if (!empty($image->caption)): ?>

                                        <div class="gallery-image-caption">
                                            <?= htmlspecialchars($image->caption) ?>
                                        </div>

                                    <?php endif; ?>

                                </div>

                            <?php endforeach; ?>

                        </div>


                        <!-- Slider Controls -->
                        <?php if (count($model['images']) > 1): ?>

                            <!-- Previous -->
                            <button type="button" class="slider-btn slider-prev" aria-label="Gambar sebelumnya">
                                &#10094;
                            </button>


                            <!-- Next -->
                            <button type="button" class="slider-btn slider-next" aria-label="Gambar berikutnya">
                                &#10095;
                            </button>


                            <!-- Dots -->
                            <div class="slider-dots">

                                <?php foreach ($model['images'] as $index => $image): ?>

                                    <button type="button" class="slider-dot <?= $index === 0 ? 'active' : '' ?>"
                                        data-slide="<?= $index ?>" aria-label="Gambar <?= $index + 1 ?>"></button>

                                <?php endforeach; ?>

                            </div>

                        <?php endif; ?>

                    </div>

                <?php endif; ?>


                <!-- Gallery Content -->
                <div class="gallery-detail-content">

                    <!-- Caption / Title -->
                    <?php if (!empty($model['gallery']->caption)): ?>

                        <h4>
                            <?= htmlspecialchars(
                                $model['gallery']->caption
                            ) ?>
                        </h4>

                    <?php else: ?>

                        <h4>
                            Gallery
                        </h4>

                    <?php endif; ?>


                    <!-- Meta -->
                    <div class="gallery-meta">

                        <?php if (!empty($model['profile'])): ?>

                            <?php
                            $email = $model['owner']->email ?? '';

                            $username = $email !== ''
                                ? explode('@', $email)[0]
                                : '';
                            ?>

                            <?php if ($username !== ''): ?>

                                <a href="/gallery/@<?= urlencode($username) ?>" class="gallery-author">

                                    <?php if (!empty($model['profile']->img)): ?>

                                        <img src="/uploads/users/<?= htmlspecialchars($model['profile']->img) ?>"
                                            alt="<?= htmlspecialchars($model['profile']->name) ?>" class="gallery-author-img">

                                    <?php endif; ?>

                                    <span>
                                        <?= htmlspecialchars($model['profile']->name) ?>
                                    </span>

                                </a>

                            <?php else: ?>

                                <div class="gallery-author">

                                    <?php if (!empty($model['profile']->img)): ?>

                                        <img src="/uploads/users/<?= htmlspecialchars($model['profile']->img) ?>"
                                            alt="<?= htmlspecialchars($model['profile']->name) ?>" class="gallery-author-img">

                                    <?php endif; ?>

                                    <span>
                                        <?= htmlspecialchars($model['profile']->name) ?>
                                    </span>

                                </div>

                            <?php endif; ?>

                        <?php endif; ?>


                        <?php if (!empty($model['gallery']->createdAt)): ?>

                            <span class="gallery-date">
                                <?= date(
                                    'd M Y',
                                    strtotime($model['gallery']->createdAt)
                                ) ?>
                            </span>

                        <?php endif; ?>

                    </div>


                    <!-- Divider -->
                    <div class="neo-border-bottom gallery-divider"></div>


                    <!-- Gallery Information -->
                    <div class="gallery-info">

                        <span>
                            <?= count($model['images'] ?? []) ?>
                            gambar
                        </span>

                    </div>

                </div>

            </article>


            <!-- Gallery Actions -->
            <div class="gallery-actions">

                <!-- Share -->
                <button type="button" class="gallery-action-btn gallery-share-btn" id="gallery-share-btn">

                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        aria-hidden="true">
                        <circle cx="18" cy="5" r="3"></circle>
                        <circle cx="6" cy="12" r="3"></circle>
                        <circle cx="18" cy="19" r="3"></circle>

                        <line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line>

                        <line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line>
                    </svg>

                    <span>Bagikan</span>

                </button>

            </div>

        </div>

    </div>
</section>



<?php
$recommendedGalleries =
    $model['recommendedGalleries'] ?? [];
?>

<?php if (!empty($recommendedGalleries)): ?>

    <section class="hero gallery-recommendations">

        <div class="container">

            <div class="gallery-detail-wrapper">

                <div class="related-header">
                    <h3>
                        <span class="highlight highlight-yellow">
                            Foto Lainnya
                        </span>
                    </h3>
                </div>


                <!-- GALLERY GRID -->
                <div class="gallery-recommendations-grid">

                    <?php foreach ($recommendedGalleries as $recommended): ?>

                        <a href="/gallery/<?= htmlspecialchars($recommended['slug']) ?>"
                            class="neo-card gallery-recommendation-card">

                            <!-- IMAGE -->
                            <div class="gallery-recommendation-image">

                                <?php if (!empty($recommended['image'])): ?>

                                    <img src="/uploads/galleries/<?= htmlspecialchars($recommended['image']) ?>" alt="<?= htmlspecialchars(
                                          $recommended['caption'] ?: 'Gallery'
                                      ) ?>" loading="lazy">

                                <?php else: ?>

                                    <div class="gallery-no-image">
                                        Tidak ada gambar
                                    </div>

                                <?php endif; ?>

                            </div>


                            <!-- CONTENT -->
                            <div class="gallery-recommendation-content">

                                <h4>
                                    <?= htmlspecialchars(
                                        $recommended['caption'] ?: 'Gallery'
                                    ) ?>
                                </h4>

                                <?php if (!empty($recommended['created_at'])): ?>

                                    <span class="gallery-recommendation-date">
                                        <?= date(
                                            'd M Y',
                                            strtotime($recommended['created_at'])
                                        ) ?>
                                    </span>

                                <?php endif; ?>

                            </div>

                        </a>

                    <?php endforeach; ?>

                </div>

            </div>

        </div>

        <div style="margin-bottom: 2rem;"></div>

    </section>

<?php endif; ?>

<style>
    /* =========================================================
   RECOMMENDED GALLERIES ("Foto Lainnya")
========================================================= */

    .gallery-recommendations {
        padding-top: 0;
        padding-bottom: 80px;
    }

    .gallery-recommendations-section {
        width: 100%;
    }


    /* =========================
   HEADER
========================= */

    .gallery-recommendations .related-header {
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 3px solid var(--dark);
    }

    .gallery-recommendations .related-header h3 {
        margin: 0 0 6px 0;
        font-size: 1.6rem;
        font-weight: 800;
    }

    .gallery-recommendations .related-subtitle {
        margin: 0;
        font-size: 0.9rem;
        color: #666;
        font-weight: 500;
    }


    /* =========================
   GRID
========================= */

    .gallery-recommendations-grid {
        display: grid;

        grid-template-columns: repeat(4, 1fr);

        gap: 1.5rem;
    }


    /* =========================
   CARD
========================= */

    .neo-card.gallery-recommendation-card {
        display: flex;
        flex-direction: column;

        padding: 0;
        height: auto;

        box-sizing: border-box;

        background-color: #ffffff;
        text-decoration: none;
        color: inherit;

        overflow: hidden;

        transition:
            transform 0.3s ease,
            box-shadow 0.3s ease;
    }

    .neo-card.gallery-recommendation-card:hover {
        transform: translateY(-4px);
        box-shadow: 6px 6px 0 var(--dark);
    }


    /* =========================
   IMAGE
========================= */

    .gallery-recommendation-image {
        width: 100%;

        aspect-ratio: 4 / 3;

        overflow: hidden;
        flex-shrink: 0;

        background-color: #f0f0f0;

        margin: 0;
        padding: 0;
    }

    .gallery-recommendation-image img {
        display: block;

        width: 100%;
        height: 100%;

        margin: 0;
        padding: 0;
        border: 0;

        object-fit: cover;
        object-position: center;

        transition: transform 0.3s ease;
    }

    .neo-card.gallery-recommendation-card:hover .gallery-recommendation-image img {
        transform: scale(1.05);
    }

    .gallery-no-image {
        display: flex;
        align-items: center;
        justify-content: center;

        width: 100%;
        height: 100%;

        background-color: #f0f0f0;
        color: #999;

        font-size: 0.85rem;
        font-weight: 700;
    }


    /* =========================
   CONTENT
========================= */

    .gallery-recommendation-content {
        display: flex;
        flex-direction: column;

        gap: 0.25rem;

        padding: 1.25rem 1.5rem 1.5rem;
    }

    .gallery-recommendation-content h4 {
        margin: 0;

        font-size: 1.05rem;
        font-weight: 800;

        color: var(--dark);

        line-height: 1.3;

        /* Batasi 2 baris supaya tinggi card konsisten */
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .gallery-recommendation-date {
        margin: 0;

        font-size: 0.85rem;
        font-weight: 600;

        color: #666;
    }


    /* =========================
   RESPONSIVE
========================= */

    @media (max-width: 992px) {

        .gallery-recommendations-grid {
            grid-template-columns: repeat(3, 1fr);
        }

    }

    @media (max-width: 768px) {

        .gallery-recommendations {
            padding-top: 40px;
            padding-bottom: 60px;
        }

        .gallery-recommendations-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }

        .gallery-recommendations .related-header h3 {
            font-size: 1.3rem;
        }

        .gallery-recommendation-content {
            padding: 1rem 1.25rem 1.25rem;
        }

        .gallery-recommendation-content h4 {
            font-size: 1rem;
        }

    }
</style>

<!-- Lightbox -->
<div class="gallery-lightbox" id="gallery-lightbox" aria-hidden="true">
    <button type="button" class="lightbox-close" id="lightbox-close" aria-label="Tutup">&times;</button>
    <button type="button" class="lightbox-nav lightbox-prev" id="lightbox-prev"
        aria-label="Sebelumnya">&#10094;</button>
    <button type="button" class="lightbox-nav lightbox-next" id="lightbox-next"
        aria-label="Berikutnya">&#10095;</button>

    <div class="lightbox-stage">
        <img src="" alt="" id="lightbox-image">
    </div>
</div>
<style>
    /* =========================
   LIGHTBOX
========================= */

    .gallery-lightbox {
        position: fixed;
        inset: 0;

        display: none;

        align-items: center;
        justify-content: center;

        background: rgba(0, 0, 0, 0.85);

        z-index: 9999;

        padding: 20px;

        box-sizing: border-box;
    }

    .gallery-lightbox.active {
        display: flex;
    }

    .lightbox-stage {
        display: flex;

        align-items: center;
        justify-content: center;

        width: 100%;
        height: 100%;

        overflow: hidden;
    }

    .lightbox-stage img {
        display: block;

        width: auto;
        height: auto;

        /* Kunci: pas ke layar, tidak terpotong, tidak perlu scroll */
        max-width: 100%;
        max-height: 100%;

        object-fit: contain;

        /* Neo style: border tebal + hard shadow */
        border: 3px solid #fff;
        box-shadow: 6px 6px 0 rgba(0, 0, 0, 0.6);
        background: transparent;
    }


    /* Tombol close */
    .lightbox-close {
        position: absolute;

        top: 20px;
        right: 20px;

        width: 48px;
        height: 48px;

        display: flex;
        align-items: center;
        justify-content: center;

        background: #fff;
        color: var(--dark);

        border: 3px solid var(--dark);
        box-shadow: 4px 4px 0 var(--dark);

        font-size: 1.8rem;
        font-weight: 800;

        cursor: pointer;

        z-index: 2;
    }

    .lightbox-close:hover {
        transform: translate(-2px, -2px);
        box-shadow: 6px 6px 0 var(--dark);
    }


    /* Tombol navigasi */
    .lightbox-nav {
        position: absolute;

        top: 50%;

        transform: translateY(-50%);

        width: 52px;
        height: 52px;

        display: flex;
        align-items: center;
        justify-content: center;

        background: #fff;
        color: var(--dark);

        border: 3px solid var(--dark);
        box-shadow: 4px 4px 0 var(--dark);

        font-size: 1.5rem;
        font-weight: 800;

        cursor: pointer;

        z-index: 2;
    }

    .lightbox-nav:hover {
        transform: translateY(calc(-50% - 2px));
    }

    .lightbox-prev {
        left: 20px;
    }

    .lightbox-next {
        right: 20px;
    }


    /* Cursor di gambar slider */
    .gallery-slide-image img {
        cursor: zoom-in;
    }


    /* Responsive */
    @media (max-width: 576px) {

        .lightbox-close {
            width: 40px;
            height: 40px;
            font-size: 1.4rem;
            top: 12px;
            right: 12px;
        }

        .lightbox-nav {
            width: 42px;
            height: 42px;
            font-size: 1.2rem;
        }

        .lightbox-prev {
            left: 10px;
        }

        .lightbox-next {
            right: 10px;
        }
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function () {

        const slides = document.querySelectorAll('.gallery-slide img');
        const lightbox = document.getElementById('gallery-lightbox');
        const lightboxImg = document.getElementById('lightbox-image');
        const btnClose = document.getElementById('lightbox-close');
        const btnPrev = document.getElementById('lightbox-prev');
        const btnNext = document.getElementById('lightbox-next');

        if (!slides.length || !lightbox) return;

        let current = 0;

        /* Bangun daftar src dari gambar slider */
        const images = Array.from(slides).map(img => ({
            src: img.src,
            alt: img.alt || ''
        }));

        function openLightbox(index) {
            current = index;

            lightboxImg.src = images[current].src;
            lightboxImg.alt = images[current].alt;

            lightbox.classList.add('active');
            lightbox.setAttribute('aria-hidden', 'false');

            document.body.style.overflow = 'hidden';
        }

        function closeLightbox() {
            lightbox.classList.remove('active');
            lightbox.setAttribute('aria-hidden', 'true');

            document.body.style.overflow = '';

            lightboxImg.src = '';
        }

        function showImage(index) {
            if (index >= images.length) index = 0;
            if (index < 0) index = images.length - 1;

            current = index;

            lightboxImg.src = images[current].src;
            lightboxImg.alt = images[current].alt;
        }

        /* Klik gambar → buka lightbox */
        slides.forEach((img, i) => {
            img.addEventListener('click', () => openLightbox(i));
        });

        /* Tombol close */
        btnClose.addEventListener('click', closeLightbox);

        /* Klik area gelap → close */
        lightbox.addEventListener('click', function (e) {
            if (e.target === lightbox || e.target.classList.contains('lightbox-stage')) {
                closeLightbox();
            }
        });

        /* Navigasi */
        if (btnPrev) btnPrev.addEventListener('click', () => showImage(current - 1));
        if (btnNext) btnNext.addEventListener('click', () => showImage(current + 1));

        /* Keyboard */
        document.addEventListener('keydown', function (e) {
            if (!lightbox.classList.contains('active')) return;

            if (e.key === 'Escape') closeLightbox();
            if (e.key === 'ArrowLeft') showImage(current - 1);
            if (e.key === 'ArrowRight') showImage(current + 1);
        });

    });
</script>

<style>
    /* =========================
       GALLERY DETAIL
    ========================= */

    .gallery-detail {
        padding-top: 140px;
    }

    .hero {
        padding-bottom: 20px;
    }

    .gallery-detail-wrapper {
        width: 100%;
        max-width: 900px;
        margin: 0 auto;
    }


    /* =========================
       BREADCRUMB
    ========================= */

    .breadcrumb {
        display: flex;
        align-items: center;
        flex-wrap: wrap;

        gap: 10px;

        margin-bottom: 30px;

        font-weight: 600;
        font-family: var(--font-heading);
    }

    .breadcrumb a {
        color: var(--dark);
        text-decoration: none;
    }

    .breadcrumb a:hover {
        color: var(--primary);
    }

    .breadcrumb span:last-child {
        color: #666;
        font-weight: 500;
    }


    /* =========================
       GALLERY CARD
    ========================= */

    .gallery-detail-card {
        padding: 0;
        overflow: hidden;

        /* Kotak mengikuti isi (gambar + konten) */
        height: auto;
    }

    .gallery-slides {
        width: 100%;
        height: auto;
    }

    .gallery-slide {
        display: none;

        position: relative;

        width: 100%;
        height: auto;
    }

    .gallery-slide.active {
        display: block;
    }


    /* =========================
   IMAGE SLIDER
========================= */

    .gallery-detail-slider {
        position: relative;

        width: 100%;

        /* Kotak menyesuaikan gambar */
        height: auto;

        overflow: hidden;

        background-color: var(--main-bg);

        border-bottom:
            var(--border-width-bold) solid var(--dark);
    }

    .gallery-slides {
        width: 100%;
        height: auto;
    }

    .gallery-slide {
        display: none;

        position: relative;

        width: 100%;
        height: auto;
    }

    .gallery-slide.active {
        display: block;
    }


    /* =========================
   SLIDE IMAGE WRAPPER
========================= */

    .gallery-slide-image {
        display: flex;

        align-items: center;

        justify-content: center;

        width: 100%;

        /* Batasi tinggi area gambar = tinggi layar
       dikurangi ruang header + breadcrumb + meta,
       supaya tidak perlu scroll. */
        max-height: calc(100vh - 260px);

        line-height: 0;

        background-color: transparent;

        overflow: hidden;
    }

    .gallery-slide-image img {
        display: block;

        width: auto;

        height: auto;

        max-width: 100%;
        max-height: calc(100vh - 260px);

        object-fit: contain;

        object-position: center;

        margin: 0 auto;
    }


    /* =========================
       IMAGE CAPTION
    ========================= */

    .gallery-image-caption {
        position: absolute;

        left: 0;
        right: 0;
        bottom: 0;

        padding: 14px 20px;

        background: rgba(0, 0, 0, 0.65);

        color: #fff;

        font-size: 0.9rem;
        font-weight: 600;

        text-align: center;
    }


    /* =========================
       SLIDER BUTTON
    ========================= */

    .slider-btn {
        position: absolute;

        top: 50%;

        transform: translateY(-50%);

        width: 50px;
        height: 50px;

        display: flex;
        align-items: center;
        justify-content: center;

        background-color: var(--light);
        color: var(--dark);

        border:
            var(--border-width) solid var(--dark);

        box-shadow:
            var(--shadow-offset) var(--shadow-offset) 0 var(--dark);

        font-size: 1.5rem;
        font-weight: 700;

        cursor: pointer;

        z-index: 5;

        transition: all 0.15s ease;
    }

    .slider-btn:hover {
        transform:
            translateY(calc(-50% - 3px));
    }

    .slider-btn:active {
        transform:
            translate(var(--shadow-offset),
                calc(-50% + var(--shadow-offset)));

        box-shadow: none;
    }

    .slider-prev {
        left: 20px;
    }

    .slider-next {
        right: 20px;
    }


    /* =========================
       SLIDER DOTS
    ========================= */

    .slider-dots {
        position: absolute;

        left: 50%;
        bottom: 60px;

        transform: translateX(-50%);

        display: flex;

        gap: 10px;

        z-index: 5;
    }

    .slider-dot {
        width: 14px;
        height: 14px;

        padding: 0;

        background-color: var(--light);

        border:
            2px solid var(--dark);

        box-shadow:
            2px 2px 0 var(--dark);

        cursor: pointer;
    }

    .slider-dot.active {
        background-color: var(--primary);
    }


    /* =========================
       GALLERY CONTENT
    ========================= */

    .gallery-detail-content {
        padding: 40px;
    }

    .gallery-detail-content h4 {
        margin-bottom: 20px;

        font-size: 1.6rem;
        font-weight: 800;
    }


    /* =========================
       META
    ========================= */

    .gallery-meta {
        display: flex;

        align-items: center;

        gap: 20px;

        flex-wrap: wrap;

        margin-bottom: 30px;
    }

    .gallery-author {
        display: flex;

        align-items: center;

        gap: 10px;

        font-size: .95rem;

        font-weight: 700;
    }

    .gallery-author-img {
        width: 36px;
        height: 36px;

        border-radius: 50%;

        object-fit: cover;

        border: 2px solid var(--dark);
    }

    .gallery-date {
        font-size: .95rem;

        font-weight: 600;
    }


    /* =========================
       DIVIDER
    ========================= */

    .gallery-divider {
        margin-bottom: 25px;
    }


    /* =========================
       GALLERY INFO
    ========================= */

    .gallery-info {
        font-size: 0.9rem;

        font-weight: 600;

        color: #666;
    }


    /* =========================
       ACTIONS
    ========================= */

    .gallery-actions {
        display: flex;

        align-items: center;

        justify-content: flex-start;

        margin: 24px 0;

        padding: 14px 18px;

        border: 2px solid var(--dark);

        background: #fff;
    }

    .gallery-action-btn {
        display: inline-flex;

        align-items: center;
        justify-content: center;

        gap: 7px;

        border: 2px solid var(--dark);

        background: #fff;

        padding: 8px 14px;

        font-family: inherit;

        font-size: 14px;

        font-weight: 700;

        cursor: pointer;

        transition:
            transform 0.15s ease,
            box-shadow 0.15s ease,
            background 0.15s ease;
    }

    .gallery-action-btn:hover {
        transform: translate(-2px, -2px);

        box-shadow:
            3px 3px 0 var(--dark);
    }

    .gallery-action-btn:active {
        transform: translate(0, 0);

        box-shadow: none;
    }


    /* =========================
       TABLET
    ========================= */

    @media (max-width: 768px) {

        .gallery-detail {
            padding-top: 110px;
        }

        .gallery-detail-content {
            padding: 25px;
        }

        .slider-btn {
            width: 40px;
            height: 40px;

            font-size: 1.2rem;
        }

        .slider-prev {
            left: 10px;
        }

        .slider-next {
            right: 10px;
        }


    }


    /* =========================
       MOBILE
    ========================= */

    @media (max-width: 576px) {

        .gallery-detail-content {
            padding: 20px;
        }

        .gallery-detail-content h4 {
            font-size: 1.4rem;
        }

        .gallery-image-caption {
            padding: 10px 14px;

            font-size: 0.8rem;
        }

        .slider-btn {
            width: 36px;
            height: 36px;

            font-size: 1rem;
        }

        .slider-prev {
            left: 8px;
        }

        .slider-next {
            right: 8px;
        }


        .slider-dot {
            width: 11px;
            height: 11px;
        }

    }
</style>


<script>

    /* =========================
       GALLERY SHARE
    ========================= */

    document.addEventListener(
        'DOMContentLoaded',
        function () {

            const shareButton =
                document.getElementById(
                    'gallery-share-btn'
                );

            if (!shareButton) {
                return;
            }

            shareButton.addEventListener(
                'click',
                async function () {

                    const shareData = {

                        title:
                            <?= json_encode(
                                $model['gallery']->caption
                                ?? 'Gallery'
                            ) ?>,

                        text:
                            'Lihat gallery ini',

                        url:
                            window.location.href
                    };


                    /* Browser mendukung Web Share API */

                    if (navigator.share) {

                        try {

                            await navigator.share(
                                shareData
                            );

                        } catch (error) {

                            // User membatalkan share.
                            // Tidak perlu melakukan apa-apa.

                        }

                        return;
                    }


                    /* Browser tidak mendukung Web Share API
                       → copy URL
                    */

                    try {

                        await navigator.clipboard.writeText(
                            window.location.href
                        );

                        const originalText =
                            shareButton
                                .querySelector('span')
                                .textContent;

                        shareButton
                            .querySelector('span')
                            .textContent =
                            'Link disalin';


                        setTimeout(
                            function () {

                                shareButton
                                    .querySelector('span')
                                    .textContent =
                                    originalText;

                            },
                            2000
                        );

                    } catch (error) {

                        alert(
                            'Silakan salin URL halaman ini secara manual.'
                        );

                    }

                }
            );

        }
    );

</script>

<script>

    /* =========================
       GALLERY SLIDER
    ========================= */

    document.addEventListener(
        'DOMContentLoaded',
        function () {

            const slides =
                document.querySelectorAll(
                    '.gallery-slide'
                );

            const dots =
                document.querySelectorAll(
                    '.slider-dot'
                );

            const prevButton =
                document.querySelector(
                    '.slider-prev'
                );

            const nextButton =
                document.querySelector(
                    '.slider-next'
                );

            let currentSlide = 0;


            /*
            |--------------------------------------------------------------------------
            | Tidak ada gambar
            |--------------------------------------------------------------------------
            */

            if (slides.length === 0) {
                return;
            }


            /*
            |--------------------------------------------------------------------------
            | Hanya satu gambar
            |--------------------------------------------------------------------------
            */

            if (slides.length <= 1) {
                return;
            }


            /*
            |--------------------------------------------------------------------------
            | Show Slide
            |--------------------------------------------------------------------------
            */

            function showSlide(index) {

                if (index >= slides.length) {

                    currentSlide = 0;

                } else if (index < 0) {

                    currentSlide =
                        slides.length - 1;

                } else {

                    currentSlide = index;

                }


                slides.forEach(
                    function (slide) {

                        slide.classList.remove(
                            'active'
                        );

                    }
                );


                dots.forEach(
                    function (dot) {

                        dot.classList.remove(
                            'active'
                        );

                    }
                );


                slides[currentSlide]
                    .classList.add('active');


                if (dots[currentSlide]) {

                    dots[currentSlide]
                        .classList.add('active');

                }

            }


            /*
            |--------------------------------------------------------------------------
            | Next
            |--------------------------------------------------------------------------
            */

            if (nextButton) {

                nextButton.addEventListener(
                    'click',
                    function () {

                        showSlide(
                            currentSlide + 1
                        );

                    }
                );

            }


            /*
            |--------------------------------------------------------------------------
            | Previous
            |--------------------------------------------------------------------------
            */

            if (prevButton) {

                prevButton.addEventListener(
                    'click',
                    function () {

                        showSlide(
                            currentSlide - 1
                        );

                    }
                );

            }


            /*
            |--------------------------------------------------------------------------
            | Dots
            |--------------------------------------------------------------------------
            */

            dots.forEach(
                function (dot) {

                    dot.addEventListener(
                        'click',
                        function () {

                            const index =
                                Number(
                                    this.dataset.slide
                                );

                            showSlide(index);

                        }
                    );

                }
            );

        }
    );

</script>