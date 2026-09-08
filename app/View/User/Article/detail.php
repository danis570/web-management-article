<!-- Article Detail -->
<section class="hero article-detail">
    <div class="container">

        <div class="article-detail-wrapper">

            <!-- Breadcrumb -->
            <nav class="breadcrumb" aria-label="Breadcrumb">
                <a href="/">Home</a>
                <span>/</span>
                <a href="/article">Artikel</a>
            </nav>

            <!-- Tags -->
            <?php if (!empty($model['article']['tags'])): ?>
                <div class="article-tags">
                    <?php foreach (explode(',', $model['article']['tags']) as $tag): ?>

                        <span class="article-tag">
                            <?= htmlspecialchars(trim($tag)) ?>
                        </span>

                    <?php endforeach; ?>

                </div>
            <?php endif; ?>

            <!-- Article -->
            <article class="neo-card article-detail-card">


                <!-- Image Slider -->
                <?php if (!empty($model['images'])): ?>

                    <div class="article-detail-slider">

                        <div class="article-slides">

                            <?php foreach ($model['images'] as $index => $image): ?>

                                <div class="article-slide <?= $index === 0 ? 'active' : '' ?>">
                                    <img src="/uploads/articles/<?= htmlspecialchars($image['image']) ?>" alt="<?= htmlspecialchars(
                                          $image['caption']
                                          ?: ($model['article']['title'] ?? 'Artikel')
                                      ) ?>">

                                    <?php if (!empty($image['caption'])): ?>
                                        <div class="article-image-caption">
                                            <?= htmlspecialchars($image['caption']) ?>
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


                <!-- Article Content -->
                <div class="article-detail-content">

                    <!-- Title -->
                    <h4>
                        <?= htmlspecialchars(
                            $model['article']['title'] ?? 'Judul Artikel'
                        ) ?>
                    </h4>


                    <!-- Meta -->
                    <div class="article-meta">

                        <?php if (!empty($model['article']['authors'])): ?>

                            <div class="article-author">

                                <?php
                                $authors = explode(
                                    ', ',
                                    $model['article']['authors']
                                );

                                $authorImages = !empty($model['article']['author_images'])
                                    ? explode(',', $model['article']['author_images'])
                                    : [];
                                ?>

                                <?php foreach ($authors as $index => $author): ?>

                                    <div class="author-item">

                                        <?php if (!empty($authorImages[$index])): ?>

                                            <img src="<?= htmlspecialchars($authorImages[$index]) ?? '/uploads/user-img/default-img-user.png' ?>"
                                                alt="<?= htmlspecialchars($author) ?>" class="author-avatar">

                                        <?php else: ?>

                                            <div class="author-avatar author-avatar-placeholder">
                                                <?= strtoupper(substr($author, 0, 1)) ?>
                                            </div>

                                        <?php endif; ?>

                                        <span class="article-date">
                                            <?= htmlspecialchars($author) ?>
                                        </span>

                                    </div>

                                <?php endforeach; ?>

                            </div>

                        <?php endif; ?>


                        <?php if (!empty($model['article']['created_at'])): ?>

                            <span class="article-date">
                                <?= date(
                                    'd M Y',
                                    strtotime($model['article']['created_at'])
                                ) ?>
                            </span>

                        <?php endif; ?>

                    </div>


                    <!-- Divider -->
                    <div class="neo-border-bottom article-divider"></div>


                    <!-- Article Body -->
                    <div class="article-body">

                        <?= $model['article']['content'] ?? '' ?>

                    </div>

                </div>

            </article>

        </div>

    </div>
</section>


<style>
    /* =========================
       ARTICLE DETAIL
    ========================= */

    .article-detail {
        padding-top: 140px;
    }

    .article-detail-wrapper {
        width: 100%;
        max-width: 900px;
        margin: 0 auto;
    }

    .article-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-bottom: 2rem;
    }

    .article-tag {
        display: inline-block;
        padding: 0.25rem 0.6rem;
        font-size: 0.75rem;
        line-height: 1;
        font-weight: 600;
        border: 2px solid #000;
        box-shadow: 2px 2px 0 #000;
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
       ARTICLE CARD
    ========================= */

    .article-detail-card {
        padding: 0;
        overflow: hidden;
    }

    .article-meta {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
        font-size: 0.85rem;
        margin-top: 1rem;
    }

    .article-author {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .author-item {
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }

    .author-avatar {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        object-fit: cover;
        border: 1.5px solid #000;
    }

    .author-avatar-placeholder {
        display: flex;
        align-items: center;
        justify-content: center;
        background: #eee;
        font-size: 0.75rem;
        font-weight: 700;
    }

    /* =========================
       IMAGE SLIDER
    ========================= */

    .article-detail-slider {
        position: relative;

        width: 100%;
        height: 450px;

        overflow: hidden;

        background-color: var(--dark);

        border-bottom: var(--border-width-bold) solid var(--dark);
    }

    .article-slides {
        width: 100%;
        height: 100%;
    }

    .article-slide {
        display: none;

        width: 100%;
        height: 100%;
    }

    .article-slide.active {
        display: block;
    }

    .article-slide img {
        width: 100%;
        height: 100%;

        object-fit: cover;

        display: block;
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

        border: var(--border-width) solid var(--dark);

        box-shadow:
            var(--shadow-offset) var(--shadow-offset) 0 var(--dark);

        font-size: 1.5rem;
        font-weight: 700;

        z-index: 5;

        transition: all 0.15s ease;
    }

    .slider-btn:hover {
        transform: translateY(calc(-50% - 3px));
    }

    .slider-btn:active {
        transform: translate(var(--shadow-offset),
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
        bottom: 20px;

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

        border: 2px solid var(--dark);

        box-shadow: 2px 2px 0 var(--dark);

        cursor: pointer;
    }

    .slider-dot.active {
        background-color: var(--primary);
    }


    /* =========================
       ARTICLE CONTENT
    ========================= */

    .article-detail-content {
        padding: 40px;
    }

    .article-detail-content h1 {
        margin-bottom: 20px;
    }


    /* =========================
       META
    ========================= */

    .article-meta {
        display: flex;

        align-items: center;

        gap: 15px;

        flex-wrap: wrap;

        margin-bottom: 30px;
    }

    .article-date {
        font-size: 0.95rem;
        font-weight: 600;
    }


    /* =========================
       DIVIDER
    ========================= */

    .article-divider {
        margin-bottom: 30px;
    }


    /* =========================
       ARTICLE BODY
    ========================= */

    .article-body {
        font-size: 1.1rem;
        line-height: 1.8;
    }

    .article-body p {
        margin-bottom: 20px;
    }

    .article-body img {
        max-width: 100%;
        height: auto;
    }


    /* =========================
       TABLET
    ========================= */

    @media (max-width: 768px) {

        .article-detail {
            padding-top: 110px;
        }

        .article-detail-slider {
            height: 300px;
        }

        .article-detail-content {
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

        .article-detail-slider {
            height: 220px;
        }

        .article-detail-content {
            padding: 20px;
        }

        .article-detail-content h1 {
            font-size: 2rem;
        }

        .article-body {
            font-size: 1rem;
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

        .slider-dots {
            bottom: 12px;
        }

        .slider-dot {
            width: 11px;
            height: 11px;
        }

    }
</style>

<script>

    document.addEventListener('DOMContentLoaded', function () {

        const slides = document.querySelectorAll('.article-slide');
        const dots = document.querySelectorAll('.slider-dot');

        const prevButton = document.querySelector('.slider-prev');
        const nextButton = document.querySelector('.slider-next');

        let currentSlide = 0;


        function showSlide(index) {

            if (index >= slides.length) {
                currentSlide = 0;
            } else if (index < 0) {
                currentSlide = slides.length - 1;
            } else {
                currentSlide = index;
            }


            slides.forEach(function (slide) {
                slide.classList.remove('active');
            });


            dots.forEach(function (dot) {
                dot.classList.remove('active');
            });


            slides[currentSlide].classList.add('active');

            dots[currentSlide].classList.add('active');

        }


        nextButton.addEventListener('click', function () {

            showSlide(currentSlide + 1);

        });


        prevButton.addEventListener('click', function () {

            showSlide(currentSlide - 1);

        });


        dots.forEach(function (dot) {

            dot.addEventListener('click', function () {

                const index = Number(this.dataset.slide);

                showSlide(index);

            });

        });

    });

</script>