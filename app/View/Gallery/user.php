<section class="hero">

    <div class="container">

        <!-- =====================================================
             HEADER (TITLE + SUBTITLE + SEARCH)
        ====================================================== -->
        <div class="gallery-header">

            <div class="gallery-header-text">

                <h1 class="gallery-page-title">
                    Gallery <?= htmlspecialchars($model['username']) ?>
                </h1>

                <p class="gallery-page-subtitle">
                    Semua gallery milik
                    <?= htmlspecialchars($model['user']->email) ?>
                </p>

            </div>

            <div class="search-container">

                <div class="article-search">

                    <input type="search" id="gallery-search" placeholder="Cari gallery..." autocomplete="off">

                </div>

            </div>

        </div>


        <!-- =====================================================
             GALLERY LIST (PINTEREST MASONRY)
        ====================================================== -->
        <?php if (!empty($model['galleries'])): ?>

            <div id="gallery-list" class="gallery-flex-masonry">

                <?php
                $columns = [[], [], [], []];

                foreach (
                    $model['galleries']
                    as $index => $gallery
                ) {
                    $columnIndex = $index % 4;
                    $columns[$columnIndex][] = $gallery;
                }
                ?>

                <?php foreach ($columns as $column): ?>

                    <div class="masonry-col">

                        <?php foreach ($column as $gallery): ?>

                            <?php
                            $previewImage =
                                $model['galleryImages'][$gallery->id]
                                ?? null;
                            ?>

                            <a href="/gallery/<?= urlencode($gallery->slug) ?>" class="neo-card gallery-card">

                                <?php if ($previewImage): ?>

                                    <div class="gallery-image">

                                        <img src="/uploads/galleries/<?= htmlspecialchars($previewImage->image) ?>"
                                            alt="<?= htmlspecialchars($gallery->caption ?? 'Gallery') ?>" loading="lazy">

                                    </div>

                                <?php else: ?>

                                    <div class="gallery-image gallery-image-placeholder">
                                        <span>No Image</span>
                                    </div>

                                <?php endif; ?>


                                <div class="gallery-content">

                                    <div class="gallery-header-box">

                                        <h3 class="gallery-title">
                                            <?= htmlspecialchars(
                                                $gallery->caption
                                                ?? 'Untitled Gallery'
                                            ) ?>
                                        </h3>

                                    </div>

                                    <div class="gallery-info">

                                        <p class="gallery-date">
                                            <?= date(
                                                'd M Y',
                                                strtotime(
                                                    $gallery->createdAt
                                                )
                                            ) ?>
                                        </p>

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
            <div id="no-result" class="text-center mt-md" style="display: none;">
                <p>Gallery tidak ditemukan.</p>
            </div>

        <?php else: ?>

            <!-- =================================================
                 EMPTY GALLERY
            ================================================== -->
            <div class="neo-card empty-gallery">
                <p>
                    <?= htmlspecialchars($model['username']) ?>
                    belum memiliki gallery.
                </p>
            </div>

        <?php endif; ?>

    </div>

</section>


<style>
    /* =========================================================
       CSS VARIABLES (FALLBACK)
    ========================================================= */
    :root {
        --dark: #111827;
        --muted: #6b7280;
        --light-bg: #f3f4f6;
        --border-width: 3px;
        --radius: 16px;
        --shadow-sm: 4px 4px 0px var(--dark);
        --shadow-md: 5px 5px 0px var(--dark);
        --shadow-lg: 8px 8px 0px var(--dark);
        --gap: 1.25rem;
    }


    /* =========================================================
       HEADER (TITLE + SUBTITLE + SEARCH)
    ========================================================= */
    .gallery-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1.5rem;
        margin-bottom: 2.5rem;
    }

    .gallery-header-text {
        min-width: 0;
    }

    .gallery-page-title {
        margin: 0 0 0.5rem 0;
        font-size: 2rem;
        font-weight: 800;
        color: var(--dark);
        line-height: 1.2;
    }

    .gallery-page-subtitle {
        margin: 0;
        font-size: 1rem;
        font-weight: 500;
        color: var(--dark);
        opacity: 0.7;
    }


    /* =========================================================
       SEARCH
    ========================================================= */
    .search-container {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-shrink: 0;
    }

    .article-search {
        position: relative;
    }

    #gallery-search {
        width: 260px;
        padding: 0.65rem 1rem;
        font-size: 0.95rem;
        font-weight: 500;
        color: var(--dark);
        background-color: #ffffff;
        border: var(--border-width) solid var(--dark);
        border-radius: 12px;
        box-shadow: var(--shadow-sm);
        outline: none;
        box-sizing: border-box;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    #gallery-search::placeholder {
        color: var(--dark);
        opacity: 0.5;
    }

    #gallery-search:focus {
        transform: translate(-2px, -2px);
        box-shadow: 6px 6px 0px var(--dark);
    }


    /* =========================================================
       MASONRY FLEX — 4 KOLOM
    ========================================================= */
    .gallery-flex-masonry {
        display: flex;
        gap: var(--gap);
        width: 100%;
        box-sizing: border-box;
    }

    .masonry-col {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: var(--gap);
        min-width: 0;
    }


    /* =========================================================
       GALLERY CARD (NEO)
    ========================================================= */
    .neo-card.gallery-card {
        display: block;
        width: 100%;
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        background-color: #ffffff;
        border: var(--border-width) solid var(--dark);
        border-radius: var(--radius);
        box-shadow: var(--shadow-md);
        text-decoration: none;
        color: inherit;
        overflow: hidden;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .neo-card.gallery-card:hover {
        transform: translate(-3px, -3px);
        box-shadow: var(--shadow-lg);
    }

    .gallery-card.is-hidden {
        display: none !important;
    }


    /* =========================================================
       IMAGE
    ========================================================= */
    .gallery-card .gallery-image {
        width: 100%;
        overflow: hidden;
        margin: 0;
        padding: 0;
        background-color: var(--light-bg);
        border-bottom: var(--border-width) solid var(--dark);
    }

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
        color: var(--muted);
        font-weight: 700;
        font-size: 0.85rem;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        border-bottom: var(--border-width) solid var(--dark);
    }


    /* =========================================================
       CONTENT
    ========================================================= */
    .gallery-card .gallery-content {
        padding: 14px 0 0;
    }

    .gallery-card .gallery-header-box {
        padding-left: 1rem;
        padding-right: 1rem;
    }

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

    .gallery-card .gallery-info {
        margin-top: 0.75rem;
        padding-left: 1rem;
        padding-right: 1rem;
        padding-bottom: 1rem;
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .gallery-card .gallery-date {
        margin: 0;
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--dark);
        opacity: 0.7;
    }


    /* =========================================================
       EMPTY STATE (SAMA DENGAN NEO-CARD)
    ========================================================= */
    .neo-card.empty-gallery {
        padding: 2rem;
        text-align: center;
        background-color: #ffffff;
        border: var(--border-width) solid var(--dark);
        border-radius: var(--radius);
        box-shadow: var(--shadow-md);
    }

    .neo-card.empty-gallery p {
        margin: 0;
        font-weight: 600;
        color: var(--dark);
    }


    /* =========================================================
       NO RESULT
    ========================================================= */
    #no-result p {
        margin: 0;
        font-weight: 600;
        color: var(--dark);
        opacity: 0.7;
    }


    /* =========================================================
       RESPONSIVE — TABLET (3 KOLOM)
    ========================================================= */
    @media (max-width: 1024px) {

        .gallery-flex-masonry .masonry-col:nth-child(4) {
            display: none !important;
        }

        .gallery-page-title {
            font-size: 1.75rem;
        }

        #gallery-search {
            width: 200px;
        }
    }


    /* =========================================================
       RESPONSIVE — MOBILE (2 KOLOM)
    ========================================================= */
    @media (max-width: 768px) {

        /* Header turun jadi kolom */
        .gallery-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        /* Search melebar penuh */
        .search-container {
            width: 100%;
            display: flex;
        }

        .article-search {
            width: 100%;
            flex-grow: 1;
        }

        #gallery-search {
            width: 100%;
            box-sizing: border-box;
        }

        /* Masonry jadi 2 kolom */
        .gallery-flex-masonry {
            gap: 1rem;
        }

        .gallery-flex-masonry .masonry-col:nth-child(3),
        .gallery-flex-masonry .masonry-col:nth-child(4) {
            display: none !important;
        }

        .masonry-col {
            gap: 1rem;
        }

        .neo-card.gallery-card {
            border-width: 2px;
            box-shadow: var(--shadow-sm);
        }

        .gallery-card .gallery-title {
            font-size: 0.95rem;
        }

        .gallery-card .gallery-image-placeholder {
            border-bottom-width: 2px;
        }

        .gallery-page-title {
            font-size: 1.5rem;
        }

        .gallery-page-subtitle {
            font-size: 0.9rem;
        }
    }
</style>


<script>
    document.addEventListener(
        'DOMContentLoaded',
        function () {

            const searchInput =
                document.getElementById('gallery-search');

            const galleryList =
                document.getElementById('gallery-list');

            const noResult =
                document.getElementById('no-result');

            if (!galleryList || !searchInput) {
                return;
            }

            searchInput.addEventListener(
                'input',
                function () {

                    const keyword =
                        this.value.toLowerCase().trim();

                    const galleries =
                        galleryList.querySelectorAll('.gallery-card');

                    let found = false;

                    galleries.forEach(gallery => {

                        const fullText =
                            gallery.textContent.toLowerCase();

                        if (fullText.includes(keyword)) {
                            gallery.classList.remove('is-hidden');
                            found = true;
                        } else {
                            gallery.classList.add('is-hidden');
                        }
                    });

                    if (noResult) {
                        noResult.style.display =
                            found ? 'none' : 'block';
                    }
                }
            );
        }
    );
</script>