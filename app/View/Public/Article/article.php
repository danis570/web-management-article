<section class="hero">

    <div class="container">

        <div class="hero-content">

            <div class="hero-text">

                <div class="article-header" style="display: flex; justify-content: space-between; align-items: center;">

                    <h3>
                        Semua
                        <span class="highlight highlight-yellow">
                            <?= htmlspecialchars($model['title'] ?? 'Artikel') ?>
                        </span>
                    </h3>

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

        const articles =
            document.querySelectorAll('.article-card');

        const noResult =
            document.getElementById('no-result');


        if (!searchInput || articles.length === 0) {
            return;
        }


        searchInput.addEventListener('input', function () {

            const keyword =
                this.value.toLowerCase().trim();

            let found = false;


            articles.forEach(article => {

                const fullText =
                    article.textContent.toLowerCase();


                if (fullText.includes(keyword)) {

                    article.classList.remove('is-hidden');

                    found = true;

                } else {

                    article.classList.add('is-hidden');

                }

            });


            if (noResult) {

                noResult.style.display =
                    found ? 'none' : 'block';

            }

        });

    });

</script>