<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <div class="hero-content">
            <div class="hero-text">
                <div class="article-header">
                    <h3>
                        Semua <span class="highlight highlight-yellow">Artikel</span>
                    </h3>

                    <div class="article-search">
                        <input type="search" id="article" placeholder="Cari artikel...">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container" style="margin-top: 3.5rem;">
        <div class="component-example">

            <?php if (isset($model['article']) && !empty($model['article'])) { ?>

                <div class="grid grid-cols-4 gap-grid-md mt-md">

                    <?php foreach ($model['article'] as $article) { ?>

                        <div class="neo-card article">
                            <div class="article-image">
                                <img src="/1.png" alt="Placeholder" class="neo-image">
                            </div>
                            <p>
                                <?= $article['title'] ?>
                            </p>

                            <button class="neo-btn neo-btn-secondary neo-btn-sm mt-md" style="margin-top: -20;">
                                Read More
                            </button>
                        </div>

                    <?php } ?>

                </div>

                <div id="no-result" style="display: none;" class="text-center mt-md">
                    <p>Artikel tidak ditemukan.</p>
                </div>

            <?php } else { ?>

                <div class="text-center mt-md">
                    <p>
                        <?= $model['emptyArticle'] ?>
                    </p>
                </div>

            <?php } ?>

        </div>
    </div>

</section>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('article');
        const articles = document.querySelectorAll('.article');
        const noResult = document.getElementById('no-result');

        if (!searchInput || articles.length === 0) {
            return;
        }

        searchInput.addEventListener('input', function () {
            const keyword = this.value.toLowerCase().trim();
            let found = false;

            articles.forEach(article => {
                // Mengambil teks dari judul artikel saja agar pencarian lebih akurat
                const titleText = article.querySelector('h5') ? article.querySelector('h5').innerText.toLowerCase() : article.innerText.toLowerCase();

                if (titleText.includes(keyword)) {
                    article.classList.remove('is-hidden'); // Munculkan kartu
                    found = true;
                } else {
                    article.classList.add('is-hidden');    // Sembunyikan kartu
                }
            });

            // Menampilkan pesan jika tidak ada artikel yang cocok
            if (noResult) {
                noResult.style.display = found ? 'none' : 'block';
            }
        });
    });
</script>