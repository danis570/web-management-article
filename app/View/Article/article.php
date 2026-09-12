<section class="hero">

    <div class="container">

        <div class="hero-content">

            <div class="hero-text">

                <div class="article-header" style="display: flex; justify-content: space-between; align-items: center;">

                    <h3>
                        <span id="article-title-prefix">Semua</span>

                        <span class="highlight highlight-yellow" id="article-title">
                            <?= htmlspecialchars($model['title'] ?? 'Artikel') ?>
                        </span>
                    </h3>

                    <script>
                        const hash = window.location.hash;

                        const prefix =
                            document.getElementById('article-title-prefix');

                        const title =
                            document.getElementById('article-title');

                        if (hash) {

                            const tagName =
                                decodeURIComponent(hash.substring(1));

                            prefix.textContent = 'Article';

                            title.textContent = `#${tagName}`;
                        }
                    </script>


                    <div class="search-container" style="display: flex; align-items: center; gap: 1rem;">

                        <div class="article-search">

                            <input type="search" id="article-search" placeholder="Cari artikel...">

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- =====================================================
         ARTICLE LIST
    ====================================================== -->

    <div class="container" style="margin-top: 3.5rem;">

        <div class="component-example">

            <?php if (!empty($model['article'])): ?>

                <div class="grid grid-cols-4 gap-grid-md mt-md" id="article-list" style="align-items: start;">


                    <?php foreach ($model['article'] as $article): ?>

                        <a href="/article/<?= htmlspecialchars($article['slug']) ?>" class="neo-card article-card">


                            <!-- =====================================================
                                 IMAGE
                            ====================================================== -->

                            <?php if (!empty($article['image'])): ?>

                                <div class="article-image">

                                    <img src="/uploads/articles/<?= htmlspecialchars($article['image']) ?>"
                                        alt="<?= htmlspecialchars($article['title']) ?>" loading="lazy">

                                </div>

                            <?php endif; ?>


                            <!-- =====================================================
                                 ARTICLE CONTENT
                            ====================================================== -->

                            <div class="article-content">


                                <!-- =========================
                                     JUDUL
                                ========================== -->

                                <div class="article-header-box">

                                    <h4 class="article-title">

                                        <?= htmlspecialchars(
                                            $article['title']
                                        ) ?>

                                    </h4>

                                </div>


                                <!-- =========================
                                     AUTHOR & DATE
                                ========================== -->

                                <div class="article-author-info">

                                    <span class="article-author-name">

                                        <?= htmlspecialchars(
                                            $article['authors']
                                            ?? 'Unknown Author'
                                        ) ?>

                                    </span>


                                    <?php if (!empty($article['created_at'])): ?>

                                        <span class="article-date">

                                            <?= date(
                                                'd M Y',
                                                strtotime($article['created_at'])
                                            ) ?>

                                        </span>

                                    <?php endif; ?>

                                </div>

                            </div>

                        </a>

                    <?php endforeach; ?>

                </div>


                <!-- =====================================================
                     NO RESULT
                ====================================================== -->

                <div id="no-result" style="display: none;" class="text-center mt-md">

                    <p>
                        Artikel tidak ditemukan.
                    </p>

                </div>


            <?php else: ?>

                <div class="text-center mt-md">

                    <p>

                        <?= htmlspecialchars(
                            $model['emptyArticle']
                            ?? 'Belum ada artikel yang diterbitkan.'
                        ) ?>

                    </p>

                </div>

            <?php endif; ?>

        </div>

    </div>

</section>


<style>
    /* =========================================================
   ARTICLE LIST
========================================================= */

    #article-list {
        row-gap: 1.5rem !important;
    }

    #article-list .article-card {
        margin-bottom: 1.5rem;
    }


    /* =========================================================
   ARTICLE CARD
========================================================= */

    .neo-card.article-card {

        display: flex;

        flex-direction: column;

        /*
    IMPORTANT:
    Padding jangan diletakkan di card.
    Karena image harus full width.
    */

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


    /* =========================================================
   IMAGE CONTAINER
========================================================= */

    .article-card .article-image {

        width: 100%;

        /*
    Rasio gambar seperti screenshot.
    */

        aspect-ratio: 16 / 9;

        overflow: hidden;

        flex-shrink: 0;

        margin: 0;

        padding: 0;

    }


    /* =========================================================
   IMAGE
========================================================= */

    .article-card .article-image img {

        display: block;

        width: 100%;

        height: 100%;

        margin: 0;

        padding: 0;

        border: 0;

        object-fit: cover;

        object-position: center;

    }


    /* =========================================================
   ARTICLE CONTENT
========================================================= */

    .article-card .article-content {

        /*
    Jarak antara gambar dan teks.
    */

        padding-top: 20px;

    }


    /* =========================================================
   TITLE BOX
========================================================= */

    .article-card .article-header-box {

        padding-left: 1.5rem;

        padding-right: 1.5rem;

    }


    /* =========================================================
   TITLE
========================================================= */

    .article-card .article-title {

        margin: 0;

        font-size: 1.2rem;

        font-weight: 800;

        color: var(--dark);

        line-height: 1.3;

    }


    /* =========================================================
   AUTHOR INFO
========================================================= */

    .article-card .article-author-info {

        margin-top: 1rem;

        padding-left: 1.5rem;

        padding-right: 1.5rem;

        padding-bottom: 1.5rem;

        display: flex;

        flex-direction: column;

        gap: 0.25rem;

    }


    /* =========================================================
   AUTHOR
========================================================= */

    .article-card .article-author-name {

        margin: 0;

        font-size: 0.95rem;

        color: var(--dark);

    }


    /* =========================================================
   DATE
========================================================= */

    .article-card .article-date {

        margin: 0;

        font-size: 0.95rem;

        color: var(--dark);

    }


    /* =========================================================
   HOVER
========================================================= */

    .neo-card.article-card:hover {

        transform: translateY(-4px);

        box-shadow: 6px 6px 0px var(--dark);

    }


    /* =========================================================
   SEARCH / HIDDEN
========================================================= */

    .article-card.is-hidden {

        opacity: 0 !important;

        transform: scale(0) !important;

        position: absolute !important;

        pointer-events: none !important;

    }


    /* =========================================================
   MOBILE
========================================================= */

    @media (max-width: 768px) {

        #article-list {

            grid-template-columns: 1fr !important;

        }


        .article-card .article-image {

            aspect-ratio: 16 / 9;

        }

    }


    /* =========================================================
   SMALL MOBILE
========================================================= */

    @media (max-width: 480px) {

        .article-card .article-title {

            font-size: 1.1rem;

        }


        .article-card .article-header-box {

            padding-left: 1.25rem;

            padding-right: 1.25rem;

        }


        .article-card .article-author-info {

            padding-left: 1.25rem;

            padding-right: 1.25rem;

            padding-bottom: 1.25rem;

        }

    }
</style>


<script>

    document.addEventListener('DOMContentLoaded', function () {


        /* =====================================================
           ELEMENT
        ====================================================== */

        const searchInput =
            document.getElementById('article-search');

        const articleList =
            document.getElementById('article-list');

        const noResult =
            document.getElementById('no-result');

        const titleElement =
            document.getElementById('article-page-title');


        if (!articleList) {

            return;

        }


        /* =====================================================
           SIMPAN ARTICLE DEFAULT
        ====================================================== */

        const defaultArticleList =
            articleList.innerHTML;


        /* =====================================================
           ESCAPE HTML
        ====================================================== */

        function escapeHtml(value) {

            const div =
                document.createElement('div');

            div.textContent =
                value ?? '';

            return div.innerHTML;

        }


        /* =====================================================
           RENDER ARTICLES
        ====================================================== */

        function renderArticles(articles) {


            articleList.innerHTML = '';


            /* =================================================
               TIDAK ADA ARTIKEL
            ================================================= */

            if (!articles || articles.length === 0) {

                articleList.innerHTML = `

                <div class="text-center">

                    <p>
                        Artikel dengan tag ini tidak ditemukan.
                    </p>

                </div>

            `;


                if (noResult) {

                    noResult.style.display =
                        'none';

                }

                return;

            }


            /* =================================================
               LOOP ARTICLES
            ================================================= */

            articles.forEach(article => {


                const articleElement =
                    document.createElement('a');


                /* =============================================
                   URL
                ============================================== */

                articleElement.href =
                    `/article/${encodeURIComponent(article.slug)}`;


                articleElement.className =
                    'neo-card article-card';


                /* =============================================
                   IMAGE
                ============================================== */

                let imageHtml = '';


                if (article.image) {

                    imageHtml = `

                    <div class="article-image">

                        <img
                            src="/uploads/articles/${escapeHtml(article.image)}"
                            alt="${escapeHtml(article.title)}"
                            loading="lazy"
                        >

                    </div>

                `;

                }


                /* =============================================
                   AUTHOR
                ============================================== */

                const author =
                    article.authors ??
                    'Unknown Author';


                /* =============================================
                   DATE
                ============================================== */

                let dateHtml = '';


                if (article.created_at) {


                    const date =
                        new Date(article.created_at);


                    dateHtml = `

                    <span class="article-date">

                        ${date.toLocaleDateString(
                        'id-ID',
                        {
                            day: '2-digit',
                            month: 'short',
                            year: 'numeric'
                        }
                    )}

                    </span>

                `;

                }


                /* =============================================
                   CARD HTML
                ============================================== */

                articleElement.innerHTML = `


                ${imageHtml}


                <div class="article-content">


                    <!-- TITLE -->

                    <div class="article-header-box">

                        <h4 class="article-title">

                            ${escapeHtml(
                    article.title
                )}

                        </h4>

                    </div>


                    <!-- AUTHOR -->

                    <div class="article-author-info">

                        <span class="article-author-name">

                            ${escapeHtml(
                    author
                )}

                        </span>


                        ${dateHtml}

                    </div>


                </div>

            `;


                /* =============================================
                   APPEND
                ============================================== */

                articleList.appendChild(
                    articleElement
                );

            });


            if (noResult) {

                noResult.style.display =
                    'none';

            }

        }


        /* =====================================================
           LOAD ARTICLE BERDASARKAN TAG
        ====================================================== */

        async function loadTag(tagSlug) {


            try {


                /* =============================================
                   LOADING
                ============================================== */

                articleList.innerHTML = `

                <div class="text-center">

                    <p>
                        Memuat artikel...
                    </p>

                </div>

            `;


                /* =============================================
                   FETCH
                ============================================== */

                const response =
                    await fetch(
                        `/article/tag?slug=${encodeURIComponent(tagSlug)}`
                    );


                /* =============================================
                   JSON
                ============================================== */

                const data =
                    await response.json();


                /* =============================================
                   CHECK RESPONSE
                ============================================== */

                if (!data.success) {

                    throw new Error(
                        data.message
                        || 'Gagal mengambil artikel.'
                    );

                }


                /* =============================================
                   UBAH JUDUL
                ============================================== */

                if (titleElement) {

                    titleElement.textContent =
                        data.tag.name;

                }


                /* =============================================
                   RENDER
                ============================================== */

                renderArticles(
                    data.articles
                );


            } catch (error) {


                console.error(error);


                articleList.innerHTML = `

                <div class="text-center">

                    <p>

                        ${escapeHtml(
                    error.message
                )}

                    </p>

                </div>

            `;

            }

        }


        /* =====================================================
           RESET KE SEMUA ARTIKEL
        ====================================================== */

        function loadAllArticles() {


            articleList.innerHTML =
                defaultArticleList;


            if (titleElement) {

                titleElement.textContent =
                    <?= json_encode(
                        $model['title']
                        ?? 'Artikel'
                    ) ?>;

            }


            /* =============================================
               RESET SEARCH
            ============================================== */

            if (searchInput) {

                searchInput.value = '';

            }

        }


        /* =====================================================
           HANDLE HASH
        ====================================================== */

        function handleHash() {


            const hash =
                window.location.hash;


            /* =============================================
               TIDAK ADA HASH
            ============================================== */

            if (!hash) {

                loadAllArticles();

                return;

            }


            /* =============================================
               AMBIL SLUG
            ============================================== */

            const tagSlug =
                decodeURIComponent(
                    hash.substring(1)
                ).trim();


            /* =============================================
               HASH KOSONG
            ============================================== */

            if (!tagSlug) {

                loadAllArticles();

                return;

            }


            /* =============================================
               LOAD TAG
            ============================================== */

            loadTag(tagSlug);

        }


        /* =====================================================
           SEARCH
        ====================================================== */

        function setupSearch() {


            if (!searchInput) {

                return;

            }


            searchInput.addEventListener(
                'input',
                function () {


                    const keyword =
                        this.value
                            .toLowerCase()
                            .trim();


                    const articles =
                        articleList.querySelectorAll(
                            '.article-card'
                        );


                    let found =
                        false;


                    /* =========================================
                       FILTER
                    ========================================== */

                    articles.forEach(article => {


                        const fullText =
                            article.textContent
                                .toLowerCase();


                        if (
                            fullText.includes(keyword)
                        ) {


                            article.classList.remove(
                                'is-hidden'
                            );


                            found =
                                true;


                        } else {


                            article.classList.add(
                                'is-hidden'
                            );

                        }

                    });


                    /* =========================================
                       NO RESULT
                    ========================================== */

                    if (noResult) {

                        noResult.style.display =
                            found
                                ? 'none'
                                : 'block';

                    }

                }
            );

        }


        /* =====================================================
           INITIALIZE
        ====================================================== */

        setupSearch();

        handleHash();


        /* =====================================================
           HASH CHANGE
        ====================================================== */

        window.addEventListener(
            'hashchange',
            handleHash
        );


    });

</script>