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

                        const prefix = document.getElementById('article-title-prefix');
                        const title = document.getElementById('article-title');

                        if (hash) {
                            const tagName = decodeURIComponent(hash.substring(1));

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


    <div class="container" style="margin-top: 3.5rem;">

        <div class="component-example">

            <?php if (!empty($model['article'])): ?>

                <div class="grid grid-cols-4 gap-grid-md mt-md" id="article-list" style="align-items: start;">

                    <?php foreach ($model['article'] as $article): ?>


                        <a href="/article/<?= htmlspecialchars($article['slug']) ?>" class="neo-card article-card">

                            <?php if (!empty($article['image'])): ?>

                                <div class="article-image">
                                    <img src="/uploads/articles/<?= htmlspecialchars($article['image']) ?>"
                                        alt="<?= htmlspecialchars($article['title']) ?>" loading="lazy">
                                </div>

                            <?php endif; ?>


                            <!-- ========================= JUDUL ========================== -->

                            <div class="article-header-box">

                                <h4 class="article-title">
                                    <?= htmlspecialchars($article['title']) ?>
                                </h4>

                            </div>


                            <!-- ========================= PENULIS & TANGGAL ========================== -->

                            <div class="article-author-info">

                                <span class="article-author-name">

                                    <?= htmlspecialchars(
                                        $article['authors'] ?? 'Unknown Author'
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

                        </a>

                    <?php endforeach; ?>

                </div>


                <!-- Tidak ditemukan -->

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
    #article-list {
        row-gap: 1.5rem !important;
    }

    #article-list .article-card {
        margin-bottom: 1.5rem;
    }

    /* =========================================
       ARTICLE CARD
    ========================================= */

    .neo-card.article-card {

        display: flex;
        flex-direction: column;

        padding: 1.5rem;

        height: auto;

        box-sizing: border-box;

        background-color: #ffffff;

        text-decoration: none;

        color: inherit;

        transition: all 0.3s ease;

    }


    /* =========================================
       TITLE
    ========================================= */

    .article-card .article-title {

        margin: 0;

        font-size: 1.2rem;

        font-weight: 800;

        color: var(--dark);

        line-height: 1.3;

    }

    .article-author-info {
        margin-top: 1rem;
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }


    /* =========================================
       CONTENT
    ========================================= */

    .article-card .article-body {

        margin-top: 0.5rem;

        margin-bottom: 0.75rem;

    }


    .article-card .article-text {

        margin: 0;

        font-size: 0.9rem;

        color: #444444;

        line-height: 1.5;

    }


    /* =========================================
       DETAILS
    ========================================= */

    .article-card .article-details {

        border-top: 2px dashed var(--dark);

        padding-top: 0.75rem;

        margin-top: auto;

    }


    .article-card .article-author {

        margin: 0;

        font-size: 0.85rem;

        font-weight: 700;

        color: var(--dark);

    }


    .article-card .article-users {

        margin: 0.35rem 0 0;

        font-size: 0.85rem;

        line-height: 1.4;

        color: #555555;

    }


    /* =========================================
       HOVER
    ========================================= */

    .neo-card.article-card:hover {

        transform: translateY(-4px);

        box-shadow: 6px 6px 0px var(--dark);

    }


    /* =========================================
       SEARCH
    ========================================= */

    .article-card.is-hidden {

        opacity: 0 !important;

        transform: scale(0) !important;

        position: absolute !important;

        pointer-events: none !important;

    }


    #article-list {

        row-gap: 1.5rem !important;

    }


    /* =========================================
       MOBILE
    ========================================= */

    @media (max-width: 768px) {

        #article-list {

            grid-template-columns: 1fr !important;

        }

    }
</style>


<script>

    document.addEventListener('DOMContentLoaded', function () {

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


        /*
        |--------------------------------------------------------------------------
        | Simpan artikel default
        |--------------------------------------------------------------------------
        */

        const defaultArticleList =
            articleList.innerHTML;


        /*
        |--------------------------------------------------------------------------
        | Escape HTML
        |--------------------------------------------------------------------------
        */

        function escapeHtml(value) {

            const div = document.createElement('div');

            div.textContent = value ?? '';

            return div.innerHTML;
        }


        /*
        |--------------------------------------------------------------------------
        | Render artikel
        |--------------------------------------------------------------------------
        */

        function renderArticles(articles) {

            articleList.innerHTML = '';

            if (!articles || articles.length === 0) {

                articleList.innerHTML = `
                <div class="text-center">
                    <p>Artikel dengan tag ini tidak ditemukan.</p>
                </div>
            `;

                if (noResult) {
                    noResult.style.display = 'none';
                }

                return;
            }


            articles.forEach(article => {

                const articleElement =
                    document.createElement('a');

                articleElement.href =
                    `/article/${encodeURIComponent(article.slug)}`;

                articleElement.className =
                    'neo-card article-card';


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


                const author =
                    article.authors ?? 'Unknown Author';


                let dateHtml = '';

                if (article.created_at) {

                    const date =
                        new Date(article.created_at);

                    dateHtml = `
                    <span class="article-date">
                        ${date.toLocaleDateString('id-ID', {
                        day: '2-digit',
                        month: 'short',
                        year: 'numeric'
                    })}
                    </span>
                `;
                }


                articleElement.innerHTML = `

                ${imageHtml}

                <div class="article-header-box">

                    <h4 class="article-title">
                        ${escapeHtml(article.title)}
                    </h4>

                </div>


                <div class="article-author-info">

                    <span class="article-author-name">
                        ${escapeHtml(author)}
                    </span>

                    ${dateHtml}

                </div>

            `;


                articleList.appendChild(articleElement);

            });


            if (noResult) {
                noResult.style.display = 'none';
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Load artikel berdasarkan tag
        |--------------------------------------------------------------------------
        */

        async function loadTag(tagSlug) {

            try {

                articleList.innerHTML = `
                <div class="text-center">
                    <p>Memuat artikel...</p>
                </div>
            `;


                const response =
                    await fetch(
                        `/article/tag?slug=${encodeURIComponent(tagSlug)}`
                    );


                const data =
                    await response.json();


                if (!data.success) {

                    throw new Error(
                        data.message || 'Gagal mengambil artikel.'
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | Ubah judul
                |--------------------------------------------------------------------------
                */

                if (titleElement) {

                    titleElement.textContent =
                        data.tag.name;
                }


                /*
                |--------------------------------------------------------------------------
                | Tampilkan artikel
                |--------------------------------------------------------------------------
                */

                renderArticles(data.articles);


            } catch (error) {

                console.error(error);


                articleList.innerHTML = `
                <div class="text-center">
                    <p>
                        ${escapeHtml(error.message)}
                    </p>
                </div>
            `;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Reset ke semua artikel
        |--------------------------------------------------------------------------
        */

        function loadAllArticles() {

            articleList.innerHTML =
                defaultArticleList;


            if (titleElement) {

                titleElement.textContent =
                    <?= json_encode($model['title'] ?? 'Artikel') ?>;
            }


            /*
            |--------------------------------------------------------------------------
            | Aktifkan kembali search
            |--------------------------------------------------------------------------
            */

            if (searchInput) {

                searchInput.value = '';
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Cek hash URL
        |--------------------------------------------------------------------------
        */

        function handleHash() {

            const hash =
                window.location.hash;


            /*
            | Tidak ada tag
            */

            if (!hash) {

                loadAllArticles();

                return;
            }


            /*
            | Buang #
            */

            const tagSlug =
                decodeURIComponent(
                    hash.substring(1)
                ).trim();


            if (!tagSlug) {

                loadAllArticles();

                return;
            }


            /*
            | Ambil artikel berdasarkan tag
            */

            loadTag(tagSlug);
        }


        /*
        |--------------------------------------------------------------------------
        | Search artikel
        |--------------------------------------------------------------------------
        */

        function setupSearch() {

            if (!searchInput) {
                return;
            }


            searchInput.addEventListener(
                'input',
                function () {

                    const keyword =
                        this.value.toLowerCase().trim();


                    const articles =
                        articleList.querySelectorAll(
                            '.article-card'
                        );


                    let found = false;


                    articles.forEach(article => {

                        const fullText =
                            article.textContent.toLowerCase();


                        if (
                            fullText.includes(keyword)
                        ) {

                            article.classList.remove(
                                'is-hidden'
                            );

                            found = true;

                        } else {

                            article.classList.add(
                                'is-hidden'
                            );
                        }

                    });


                    if (noResult) {

                        noResult.style.display =
                            found ? 'none' : 'block';
                    }

                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Jalankan
        |--------------------------------------------------------------------------
        */

        setupSearch();

        handleHash();


        /*
        |--------------------------------------------------------------------------
        | Kalau hash berubah
        |--------------------------------------------------------------------------
        */

        window.addEventListener(
            'hashchange',
            handleHash
        );

    });

</script>