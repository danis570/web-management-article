<!-- Article Detail -->
<section class="hero article-detail">
    <div class="container">

        <div class="article-detail-wrapper">

            <!-- Breadcrumb -->
            <nav class="breadcrumb" aria-label="Breadcrumb">
                <a href="/">Home</a>
                <span>/</span>
                <a href="/article">Artikel</a>
                <span>/</span>
                <span>Judul Artikel</span>
            </nav>

            <!-- Article -->
            <article class="neo-card article-detail-card">

                <!-- Image Slider -->
                <div class="article-detail-slider">

                    <div class="article-slides">

                        <div class="article-slide active">
                            <img
                                src="/1.png"
                                alt="Foto artikel 1">
                        </div>

                        <div class="article-slide">
                            <img
                                src="/2.png"
                                alt="Foto artikel 2">
                        </div>

                        <div class="article-slide">
                            <img
                                src="/3.png"
                                alt="Foto artikel 3">
                        </div>

                    </div>

                    <!-- Previous -->
                    <button type="button" class="slider-btn slider-prev">
                        &#10094;
                    </button>

                    <!-- Next -->
                    <button type="button" class="slider-btn slider-next">
                        &#10095;
                    </button>

                    <!-- Dots -->
                    <div class="slider-dots">

                        <button
                            type="button"
                            class="slider-dot active"
                            data-slide="0">
                        </button>

                        <button
                            type="button"
                            class="slider-dot"
                            data-slide="1">
                        </button>

                        <button
                            type="button"
                            class="slider-dot"
                            data-slide="2">
                        </button>

                    </div>

                </div>

                <!-- Content -->
                <div class="article-detail-content">

                    <!-- Title -->
                    <h1>
                        Judul Artikel
                    </h1>

                    <!-- Meta -->
                    <div class="article-meta">

                        <span class="neo-bubble">
                            Danish
                        </span>

                        <span class="article-date">
                            12 Januari 2026
                        </span>

                    </div>

                    <!-- Divider -->
                    <div class="neo-border-bottom article-divider"></div>

                    <!-- Body -->
                    <div class="article-body">

                        <p>
                            Lorem ipsum dolor sit amet consectetur adipisicing elit.
                            Sit non sunt libero, velit facere nobis enim, deserunt,
                            corporis quas voluptates ullam placeat ipsum distinctio
                            ut ad rerum maxime sed commodi?
                        </p>

                        <p>
                            Perspiciatis voluptatum rem et, culpa id adipisci suscipit
                            natus nulla ipsa eaque facere? Enim cumque nostrum quam
                            odit. Magni sed assumenda officia doloribus nisi odit
                            laudantium quam excepturi voluptatem laboriosam.
                        </p>

                        <p>
                            Veniam alias odit perferendis, laboriosam numquam quia
                            ipsum impedit. Ipsam rerum molestiae ullam nam, eius
                            quasi esse, nemo iusto vero quas odit non, iste officia
                            corporis officiis dolor molestias cupiditate?
                        </p>

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
            var(--shadow-offset)
            var(--shadow-offset)
            0
            var(--dark);

        font-size: 1.5rem;
        font-weight: 700;

        z-index: 5;

        transition: all 0.15s ease;
    }

    .slider-btn:hover {
        transform: translateY(calc(-50% - 3px));
    }

    .slider-btn:active {
        transform: translate(
            var(--shadow-offset),
            calc(-50% + var(--shadow-offset))
        );

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