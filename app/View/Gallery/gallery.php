<section class="hero">

    <div class="container">

        <div class="hero-content">

            <div class="hero-text">

                <div class="gallery-header" style="
                        display: flex;
                        justify-content: space-between;
                        align-items: center;
                    ">

                    <h3>

                        <span class="highlight highlight-yellow">
                            <?= htmlspecialchars(
                                $model['title'] ?? 'Gallery'
                            ) ?>
                        </span>

                    </h3>

                    <!-- =========================
                         SEARCH
                    ========================== -->

                    <div class="search-container" style="
                            display: flex;
                            align-items: center;
                            gap: 1rem;
                        ">

                        <div class="article-search">

                            <input type="search" id="gallery-search" placeholder="Cari gallery..." autocomplete="off">

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- =====================================================
         GALLERY LIST (PINTEREST MASONRY)
    ====================================================== -->

    <div class="container" style="margin-top: 3.5rem;">

        <div class="component-example">


            <?php if (!empty($model['galleries'])): ?>

                <!-- PENTING: Class diubah menjadi gallery-flex-masonry -->
                <div class="gallery-flex-masonry" id="gallery-list">

                    <?php
                    // Langkah 1: Siapkan 4 ember kolom kosong
                    $columns = [[], [], [], []];
                    $index = 0;

                    // Langkah 2: Distribusikan item secara merata ke 4 kolom (0, 1, 2, 3)
                    foreach ($model['galleries'] as $gallery) {
                        $columns[$index % 4][] = $gallery;
                        $index++;
                    }
                    ?>

                    <!-- Langkah 3: Render masing-masing kolom -->
                    <?php foreach ($columns as $colItems): ?>
                        <div class="masonry-col">
                            <?php foreach ($colItems as $gallery): ?>

                                <a href="/gallery/<?= htmlspecialchars($gallery->slug) ?>" class="neo-card gallery-card">

                                    <!-- =================================================
                             IMAGE
                        ================================================== -->
                                    <?php
                                    $previewImage = $model['galleryImages'][$gallery->id] ?? null;
                                    ?>

                                    <?php if ($previewImage !== null): ?>
                                        <div class="gallery-image">
                                            <img src="/uploads/galleries/<?= htmlspecialchars($previewImage->image) ?>"
                                                alt="<?= htmlspecialchars($gallery->caption ?? 'Gallery') ?>" loading="lazy"
                                                style="width: 100%; display: block;">
                                        </div>
                                    <?php else: ?>
                                        <div class="gallery-image gallery-image-placeholder">
                                            <span>No Image</span>
                                        </div>
                                    <?php endif; ?>

                                    <!-- =================================================
                             GALLERY CONTENT
                        ================================================== -->
                                    <div class="gallery-content">
                                        <div class="gallery-header-box">
                                            <h4 class="gallery-title">
                                                <?= htmlspecialchars($gallery->caption ?? 'Untitled Gallery') ?>
                                            </h4>
                                        </div>

                                        <div class="gallery-info">
                                            <?php if (!empty($gallery->createdAt)): ?>
                                                <span class="gallery-date">
                                                    <?= date('d M Y', strtotime($gallery->createdAt)) ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                </a>

                            <?php endforeach; ?>
                        </div>
                    <?php endforeach; ?>

                </div>

                <!-- =================================================
         NO RESULT
    ================================================== -->
                <div id="no-result" style="display: none;" class="text-center mt-md">
                    <p>Gallery tidak ditemukan.</p>
                </div>

            <?php else: ?>

                <!-- =================================================
         EMPTY GALLERY
    ================================================== -->
                <div class="text-center mt-md">
                    <p><?= htmlspecialchars($model['emptyGallery'] ?? 'Belum ada gallery.') ?></p>
                </div>

            <?php endif; ?>



        </div>

    </div>

</section>


<style>
    /* Pembungkus Utama Galeri */
    .gallery-flex-masonry {
        display: flex !important;
        gap: 1.25rem;
        /* Jarak horizontal antar kolom */
        width: 100% !important;
        box-sizing: border-box;
    }

    /* Masing-masing Lajur Kolom */
    .masonry-col {
        flex: 1;
        /* Membagi rata 4 kolom sama besar secara horizontal */
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
        /* Jarak vertikal antar kartu di dalam satu lajur */
        min-width: 0;
        /* Mencegah layout rusak akibat overflow elemen di dalamnya */
    }

    /* =========================================================
   GALLERY CARD (NEO) - Tetap Fleksibel Mengikuti Gambar
========================================================= */
    .neo-card.gallery-card {
        display: block;
        width: 100%;
        margin: 0;
        /* Margin dihapus karena sudah diatur 'gap' kontainer */
        padding: 0;
        box-sizing: border-box;
        background-color: #ffffff;
        border: 3px solid var(--dark);
        border-radius: 16px;
        box-shadow: 5px 5px 0px var(--dark);
        text-decoration: none;
        color: inherit;
        overflow: hidden;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    /* Memastikan gambar neo-card memenuhi lebar kartu agar rapi */
    .gallery-image img {
        width: 100%;
        height: auto;
        display: block;
    }


    /* =========================================================
       IMAGE CONTAINER
    ========================================================= */

    .gallery-card .gallery-image {

        width: 100%;

        overflow: hidden;

        flex-shrink: 0;

        margin: 0;

        padding: 0;

        background-color: #f3f4f6;

        border-bottom: 3px solid var(--dark);

    }


    /* =========================================================
       IMAGE
    ========================================================= */

    .gallery-card .gallery-image img {

        display: block;

        width: 100%;

        height: auto;

        margin: 0;

        padding: 0;

        border: 0;

        object-fit: cover;

        object-position: center;

    }


    /* =========================================================
       IMAGE PLACEHOLDER
    ========================================================= */

    .gallery-card .gallery-image-placeholder {

        aspect-ratio: 4 / 3;

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
       CONTENT
    ========================================================= */

    .gallery-card .gallery-content {

        padding: 14px 0 0;

    }


    /* =========================================================
       HEADER BOX
    ========================================================= */

    .gallery-card .gallery-header-box {

        padding-left: 1rem;

        padding-right: 1rem;

    }


    /* =========================================================
       TITLE
    ========================================================= */

    .gallery-card .gallery-title {

        margin: 0;

        font-size: 1rem;

        font-weight: 800;

        color: var(--dark);

        line-height: 1.3;

        display: -webkit-box;

        -webkit-line-clamp: 2;

        -webkit-box-orient: vertical;

        overflow: hidden;

    }


    /* =========================================================
       INFO
    ========================================================= */

    .gallery-card .gallery-info {

        margin-top: 0.75rem;

        padding-left: 1rem;

        padding-right: 1rem;

        padding-bottom: 1rem;

        display: flex;

        flex-direction: column;

        gap: 0.25rem;

    }


    /* =========================================================
       DATE
    ========================================================= */

    .gallery-card .gallery-date {

        margin: 0;

        font-size: 0.8rem;

        font-weight: 600;

        color: var(--dark);

        opacity: 0.7;

    }


    /* =========================================================
       HOVER (NEO LIFT)
    ========================================================= */

    .neo-card.gallery-card:hover {

        transform: translate(-3px, -3px);

        box-shadow: 8px 8px 0px var(--dark);

    }


    /* =========================================================
       HIDDEN
    ========================================================= */

    .gallery-card.is-hidden {

        display: none !important;

    }


    /* =========================================================
   RESPONSIVE — TABLET (Menjadi 3 Kolom)
========================================================= */
    @media (max-width: 1024px) {

        /* Sembunyikan kolom ke-4 karena area menyempit */
        .gallery-flex-masonry .masonry-col:nth-child(4) {
            display: none !important;
        }
    }

    /* =========================================================
   RESPONSIVE — MOBILE (Menjadi 2 Kolom)
========================================================= */
    @media (max-width: 768px) {
        .gallery-flex-masonry {
            gap: 1rem;
            /* Memperkecil jarak horizontal antar kolom */
        }

        /* Sembunyikan kolom ke-3 dan ke-4 agar tersisa 2 kolom */
        .gallery-flex-masonry .masonry-col:nth-child(3),
        .gallery-flex-masonry .masonry-col:nth-child(4) {
            display: none !important;
        }

        .masonry-col {
            gap: 1rem;
            /* Memperkecil jarak vertikal antar kartu di dalam kolom */
        }

        .neo-card.gallery-card {
            border-width: 2px;
            box-shadow: 4px 4px 0px var(--dark);
        }

        .gallery-card .gallery-title {
            font-size: 0.95rem;
        }

        /* 1. Memaksa pembungkus luar search agar memenuhi lebar layar */
        .search-container {
            width: 100% !important;
            display: flex !important;
            margin-top: 1rem;
        }

        /* 2. Memaksa elemen pencarian di dalamnya melebar penuh */
        .article-search {
            width: 100% !important;
            flex-grow: 1;
        }

        /* 3. Memaksa kotak input teks utama pas dengan batas kanan-kiri layar */
        #gallery-search {
            width: 100% !important;
            box-sizing: border-box;
            /* Mencegah input meluber keluar layar akibat padding */
        }
    }


    /* =========================================================
   HEADER MOBILE (Tetap seperti bawaan Anda)
========================================================= */
    @media (max-width: 768px) {
        .gallery-header {
            flex-direction: column;
            align-items: flex-start !important;
            gap: 1rem;
        }
    }
</style>


<script>

    document.addEventListener(
        'DOMContentLoaded',
        function () {


            /* =====================================================
               ELEMENT
            ====================================================== */

            const searchInput =
                document.getElementById(
                    'gallery-search'
                );


            const galleryList =
                document.getElementById(
                    'gallery-list'
                );


            const noResult =
                document.getElementById(
                    'no-result'
                );


            /* =====================================================
               CHECK
            ====================================================== */

            if (!galleryList) {

                return;

            }


            if (!searchInput) {

                return;

            }


            /* =====================================================
               SEARCH
            ====================================================== */

            searchInput.addEventListener(
                'input',
                function () {


                    const keyword =
                        this.value
                            .toLowerCase()
                            .trim();


                    const galleries =
                        galleryList.querySelectorAll(
                            '.gallery-card'
                        );


                    let found =
                        false;


                    /* =================================================
                       FILTER
                    ================================================== */

                    galleries.forEach(
                        gallery => {


                            const fullText =
                                gallery.textContent
                                    .toLowerCase();


                            if (
                                fullText.includes(
                                    keyword
                                )
                            ) {


                                gallery.classList.remove(
                                    'is-hidden'
                                );


                                found =
                                    true;


                            } else {


                                gallery.classList.add(
                                    'is-hidden'
                                );

                            }

                        }
                    );


                    /* =================================================
                       NO RESULT
                    ================================================== */

                    if (noResult) {

                        noResult.style.display =
                            found
                                ? 'none'
                                : 'block';

                    }

                }
            );

        }
    );

</script>