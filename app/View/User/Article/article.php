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

                    <!-- Kontainer tombol Add dan Search -->
                    <div class="search-container" style="display: flex; align-items: center; gap: 1rem;">

                        <a href="/article/add" class="neo-btn neo-btn-primary neo-btn-sm">
                            Add
                        </a>

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

            <?php if (isset($model['article']) && !empty($model['article'])) { ?>

                <div class="grid grid-cols-4 gap-grid-md mt-md" id="article-list" style="align-items: start;">

                    <?php foreach ($model['article'] as $article) { ?>

                        <div class="neo-card article-card">

                            <!-- =========================
                                 IDENTITAS & JUDUL
                            ========================== -->
                            <div class="article-header-box">

                                <h4 class="article-title">
                                    <?= htmlspecialchars($article['title']) ?>
                                </h4>

                            </div>


                            <!-- =========================
                                 ISI ARTIKEL
                            ========================== -->
                            <div class="article-body">

                                <p class="article-text">
                                    <?= htmlspecialchars(
                                        substr(
                                            strip_tags($article['content'] ?? ''),
                                            0,
                                            100
                                        )
                                    ) ?>...
                                </p>

                            </div>


                            <!-- =========================
                                 DETAIL ARTIKEL
                            ========================== -->
                            <div class="article-details">

                                <?php if (!empty($article['connected_users'])): ?>

                                    <p class="article-author">Terhubung dengan:</p>

                                    <p class="article-users">
                                        <?= htmlspecialchars($article['connected_users']) ?>
                                    </p>

                                <?php endif; ?>


                                <!-- =========================
                                     ACTION
                                ========================== -->
                                <div class="article-actions" style="
                                        display: flex;
                                        gap: 0.5rem;
                                        margin-top: 0.75rem;
                                    ">

                                    <!-- Edit -->
                                    <form action="/article/edit" method="get" style="flex: 1; margin: 0;">

                                        <button name="id" value="<?= (int) $article['id'] ?>" type="submit"
                                            class="neo-btn neo-btn-sm" style="
                                                width: 100%;
                                                background-color: var(--yellow-light);
                                                font-size: 0.8rem;
                                                padding: 0.5rem;
                                            ">

                                            Edit

                                        </button>

                                    </form>


                                    <!-- Delete -->
                                    <form action="/article/delete" method="post" style="flex: 1; margin: 0;"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus artikel ini?')">

                                        <button name="id" value="<?= (int) $article['id'] ?>" type="submit"
                                            class="neo-btn neo-btn-sm" style="
                                                width: 100%;
                                                background-color: #ff5757;
                                                color: #fff;
                                                font-size: 0.8rem;
                                                padding: 0.5rem;
                                            ">

                                            Delete

                                        </button>

                                    </form>

                                </div>

                            </div>

                        </div>

                    <?php } ?>

                </div>


                <!-- Pesan jika pencarian tidak ditemukan -->
                <div id="no-result" style="display: none;" class="text-center mt-md">

                    <p>Artikel tidak ditemukan.</p>

                </div>

            <?php } else { ?>

                <div class="text-center mt-md">

                    <p>
                        <?= htmlspecialchars(
                            $model['emptyArticle']
                            ?? 'Belum ada artikel yang diterbitkan.'
                        ) ?>
                    </p>

                </div>

            <?php } ?>

        </div>
    </div>
</section>


<style>
    /* =========================================
       ARTICLE MANAGEMENT CARD
    ========================================= */

    .neo-card.article-card {
        display: flex;
        flex-direction: column;
        justify-content: flex-start;

        padding: 1.5rem;

        height: auto;
        box-sizing: border-box;

        background-color: #ffffff;

        transition: all 0.3s ease;
    }


    /* =========================================
       ARTICLE ID
    ========================================= */

    .article-card .badge-id {
        display: inline-block;

        font-size: 0.75rem;
        font-weight: 700;

        text-transform: uppercase;

        color: #777777;

        margin-bottom: 0.25rem;
    }


    /* =========================================
       ARTICLE TITLE
    ========================================= */

    .article-card .article-title {
        margin: 0;

        font-size: 1.2rem;
        font-weight: 800;

        color: var(--dark);

        line-height: 1.3;
    }


    /* =========================================
       ARTICLE CONTENT
    ========================================= */

    .article-card .article-body {
        margin-top: 0.5rem;
        margin-bottom: 0.75rem;
    }

    .article-card .article-text {
        margin: 0;

        font-size: 0.9rem;

        color: #444444;

        line-height: 1.4;
    }


    /* =========================================
       ARTICLE DETAILS
    ========================================= */

    .article-card .article-details {
        display: flex;
        flex-direction: column;

        border-top: 2px dashed var(--dark);

        padding-top: 0.75rem;

        margin-top: auto;
    }


    /* Label "Terhubung dengan" */

    .article-card .article-author {
        margin: 0;

        font-size: 0.85rem;

        font-weight: 700;

        color: var(--dark);
    }


    /* Daftar user */

    .article-card .article-users {
        margin: 0.35rem 0 0;

        font-size: 0.85rem;

        line-height: 1.4;

        color: #555555;
    }


    /* =========================================
       ACTION BUTTON
    ========================================= */

    .article-actions {
        display: flex;

        gap: 0.5rem;

        margin-top: 0.75rem;
    }


    /* =========================================
       HOVER
    ========================================= */

    .neo-card.article-card:hover {
        transform: translateY(-4px);

        box-shadow: 6px 6px 0px var(--dark);
    }


    /* =========================================
       SEARCH RESULT
    ========================================= */

    .article-card.is-hidden {
        opacity: 0 !important;

        transform: scale(0) !important;

        position: absolute !important;

        pointer-events: none !important;

        transition: all 0.3s ease;
    }


    .neo-card.article-card {
        transition: all 0.3s ease;
    }


    /* =========================================
       GRID
    ========================================= */

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

                /*
                 * Mencari keyword ke seluruh isi card:
                 *
                 * - ID
                 * - Judul
                 * - Isi artikel
                 * - User yang terhubung
                 */
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