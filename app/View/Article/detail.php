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

            <?php if (!empty($model['article']['tags'])): ?>

                <div class="article-tags">

                    <?php foreach ($model['article']['tags'] as $tag): ?>

                        <a href="/article/#<?= urlencode($tag['slug']) ?>" class="article-tag">
                            #<?= htmlspecialchars($tag['name']) ?>
                        </a>

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

                                $authorEmails = !empty($model['article']['author_emails'])
                                    ? explode(',', $model['article']['author_emails'])
                                    : [];

                                $authorImages = !empty($model['article']['author_images'])
                                    ? explode(',', $model['article']['author_images'])
                                    : [];
                                ?>

                                <?php foreach ($authors as $index => $author): ?>

                                    <?php
                                    $email = $authorEmails[$index] ?? '';
                                    $username = $email !== ''
                                        ? explode('@', $email)[0]
                                        : '';
                                    ?>

                                    <a href="/article/@<?= urlencode($username) ?>" class="author-item">

                                        <?php if (!empty($authorImages[$index])): ?>

                                            <img src="/uploads/users/<?= htmlspecialchars($authorImages[$index]) ?>"
                                                alt="<?= htmlspecialchars($author) ?>" class="author-avatar">

                                        <?php else: ?>

                                            <div class="author-avatar author-avatar-placeholder">
                                                <?= strtoupper(substr($author, 0, 1)) ?>
                                            </div>

                                        <?php endif; ?>

                                        <span class="article-date">
                                            <?= htmlspecialchars($author) ?>
                                        </span>

                                    </a>

                                <?php endforeach; ?>

                            </div>

                        <?php endif; ?>


                        <?php if (!empty($model['article']['created_at'])): ?>

                            <span class="article-date">
                                <?= date('d M Y', strtotime(max($model['article']['updated_at'], $model['article']['created_at']))) ?>
                            </span>

                        <?php endif; ?>


                        <?php if (isset($model['article']['view_count'])): ?>

                            <span class="article-view">

                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" aria-hidden="true">
                                    <path
                                        d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>

                                <?= number_format((int) $model['article']['view_count']) ?>

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

            <!-- Article Actions -->
            <div class="article-actions">

                <!-- Share -->
                <button type="button" class="article-action-btn article-share-btn" id="article-share-btn">

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


                <!-- Like -->
                <?php
                $isLiked = !empty($model['article']['is_liked']);
                ?>

                <?php if ($isLiked): ?>

                    <form action="/article/unlike" method="POST" class="article-like-form">

                        <input type="hidden" name="article_id" value="<?= (int) $model['article']['id'] ?>">

                        <button type="submit" class="article-action-btn article-like-btn liked">

                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" aria-hidden="true">
                                <path d="M7 10v12"></path>
                                <path
                                    d="M15 5.88 14 10h5.83a2 2 0 0 1 1.92 2.56l-2.33 8A2 2 0 0 1 17.5 22H4a2 2 0 0 1-2-2v-8a2 2 0 0 1 2-2h2.76a2 2 0 0 0 1.79-1.11L12 2h0a3.13 3.13 0 0 1 3 3.88Z">
                                </path>
                            </svg>

                            <span>
                                <?= number_format(
                                    (int) $model['article']['like_count']
                                ) ?>
                            </span>

                        </button>

                    </form>

                <?php else: ?>

                    <form action="/article/like" method="POST" class="article-like-form">

                        <input type="hidden" name="article_id" value="<?= (int) $model['article']['id'] ?>">

                        <button type="submit" class="article-action-btn article-like-btn">

                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                aria-hidden="true">
                                <path d="M7 10v12"></path>
                                <path
                                    d="M15 5.88 14 10h5.83a2 2 0 0 1 1.92 2.56l-2.33 8A2 2 0 0 1 17.5 22H4a2 2 0 0 1-2-2v-8a2 2 0 0 1 2-2h2.76a2 2 0 0 0 1.79-1.11L12 2h0a3.13 3.13 0 0 1 3 3.88Z">
                                </path>
                            </svg>

                            <span>
                                <?= number_format(
                                    (int) $model['article']['like_count']
                                ) ?>
                            </span>

                        </button>

                    </form>

                <?php endif; ?>

            </div>

            <!-- Comments -->
            <section class="comments-section">

                <div class="comments-header">
                    <h4>
                        Komentar
                        <span class="highlight highlight-yellow">
                            <?= count($model['comments'] ?? []) ?>
                        </span>
                    </h4>
                </div>


                <!-- Form Komentar -->
                <?php if ($model['isLoggedIn'] ?? false): ?>

                    <form action="/comment/create" method="POST" class="comment-form">

                        <input type="hidden" name="article_id" value="<?= (int) $model['article']['id'] ?>">

                        <textarea name="content" rows="4" placeholder="Tulis komentar..." required></textarea>

                        <button type="submit" class="neo-btn">
                            Kirim Komentar
                        </button>

                    </form>

                <?php else: ?>

                    <div class="comment-login-message">
                        <p>
                            Silakan
                            <a href="/login?redirect=<?= urlencode($_SERVER['REQUEST_URI']) ?>">
                                login
                            </a>
                            untuk memberikan komentar.
                        </p>
                    </div>

                <?php endif; ?>


                <!-- Daftar Komentar -->

                <?php
                $commentsByParent = [];

                foreach ($model['comments'] ?? [] as $comment) {
                    $parentId = $comment['parent_id'] ?? 0;
                    $commentsByParent[$parentId][] = $comment;
                }

                $renderComments = function (int $parentId = 0, int $depth = 0) use (&$renderComments, $commentsByParent, $model) {
                    if (empty($commentsByParent[$parentId])) {
                        return;
                    }

                    foreach ($commentsByParent[$parentId] as $comment):
                        ?>

                        <div class="comment-wrapper">

                            <!-- Komentar -->
                            <div class="comment-item">

                                <!-- User -->
                                <div class="comment-user">
                                    <?php if (!empty($comment['img'])): ?>
                                        <img src="/uploads/users/<?= htmlspecialchars($comment['img']) ?>"
                                            alt="<?= htmlspecialchars($comment['name']) ?>" class="comment-avatar">
                                    <?php else: ?>
                                        <div class="comment-avatar comment-avatar-placeholder">
                                            <?= strtoupper(
                                                substr($comment['name'] ?? '?', 0, 1)
                                            ) ?>
                                        </div>
                                    <?php endif; ?>

                                    <div>
                                        <strong>
                                            <?= htmlspecialchars(
                                                $comment['name'] ?? 'Unknown User'
                                            ) ?>
                                        </strong>

                                        <small>
                                            <?= date(
                                                'd M Y H:i',
                                                strtotime($comment['created_at'])
                                            ) ?>
                                        </small>
                                    </div>
                                </div>

                                <!-- Content -->
                                <div class="comment-content">
                                    <?= nl2br(
                                        htmlspecialchars($comment['content'])
                                    ) ?>
                                </div>

                                <!-- Actions -->
                                <div class="comment-actions">

                                    <?php if ($model['isLoggedIn'] ?? false): ?>

                                        <?php if (!empty($comment['is_liked'])): ?>

                                            <form action="/comment/unlike" method="POST">
                                                <input type="hidden" name="comment_id" value="<?= (int) $comment['id'] ?>">

                                                <button type="submit">
                                                    ❤️ <?= (int) $comment['like_count'] ?>
                                                </button>
                                            </form>

                                        <?php else: ?>

                                            <form action="/comment/like" method="POST">
                                                <input type="hidden" name="comment_id" value="<?= (int) $comment['id'] ?>">

                                                <button type="submit">
                                                    🤍 <?= (int) $comment['like_count'] ?>
                                                </button>
                                            </form>

                                        <?php endif; ?>

                                        <button type="button" class="reply-comment-btn"
                                            data-comment-id="<?= (int) $comment['id'] ?>">
                                            Balas
                                        </button>

                                        <?php if (
                                            (int) $comment['user_id']
                                            === (int) ($model['currentUserId'] ?? 0)
                                        ): ?>

                                            <form action="/comment/delete" method="POST">
                                                <input type="hidden" name="comment_id" value="<?= (int) $comment['id'] ?>">

                                                <button type="submit">
                                                    Hapus
                                                </button>
                                            </form>

                                        <?php endif; ?>

                                    <?php else: ?>

                                        <button type="button" class="comment-action-btn comment-action-disabled"
                                            aria-disabled="true">
                                            🤍 <?= (int) $comment['like_count'] ?>
                                        </button>

                                        <button type="button" class="comment-action-btn comment-action-disabled"
                                            aria-disabled="true">
                                            Balas
                                        </button>

                                    <?php endif; ?>

                                </div>

                                <!-- Reply Form -->
                                <?php if ($model['isLoggedIn'] ?? false): ?>

                                    <form action="/comment/create" method="POST" class="reply-form"
                                        id="reply-form-<?= (int) $comment['id'] ?>" style="display: none;">

                                        <input type="hidden" name="article_id" value="<?= (int) $model['article']['id'] ?>">

                                        <input type="hidden" name="parent_id" value="<?= (int) $comment['id'] ?>">

                                        <textarea name="content" rows="3" placeholder="Tulis balasan..." required></textarea>

                                        <div class="reply-form-actions">

                                            <button type="submit" class="reply-submit-btn">
                                                Kirim Balasan
                                            </button>

                                            <button type="button" class="cancel-reply-btn"
                                                data-comment-id="<?= (int) $comment['id'] ?>">
                                                Batal
                                            </button>

                                        </div>

                                    </form>

                                <?php endif; ?>

                            </div>

                            <!-- Reply -->
                            <?php if (!empty($commentsByParent[$comment['id']])): ?>

                                <?php
                                $replyCount = count($commentsByParent[$comment['id']]);
                                ?>

                                <button type="button" class="toggle-replies-btn" data-comment-id="<?= (int) $comment['id'] ?>"
                                    aria-expanded="false">
                                    Lihat <?= $replyCount ?> balasan
                                </button>

                                <div class="comment-replies" id="replies-<?= (int) $comment['id'] ?>"
                                    style="display: none; margin-left: <?= min(($depth + 1) * 40, 200) ?>px;">

                                    <?php
                                    $renderComments(
                                        (int) $comment['id'],
                                        $depth + 1
                                    );
                                    ?>

                                </div>

                            <?php endif; ?>

                        </div>

                        <?php
                    endforeach;
                };
                ?>

                <div class="comments-list">
                    <?php $renderComments(); ?>
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function () {

                        /*
                         * =========================
                         * REPLY FORM
                         * =========================
                         */

                        document.querySelectorAll('.reply-comment-btn').forEach(button => {

                            button.addEventListener('click', function () {

                                const commentId = this.dataset.commentId;

                                const form = document.getElementById(
                                    `reply-form-${commentId}`
                                );

                                if (!form) {
                                    return;
                                }

                                form.style.display = 'block';

                                const textarea = form.querySelector('textarea');

                                if (textarea) {
                                    textarea.focus();
                                }
                            });

                        });


                        /*
                         * =========================
                         * CANCEL REPLY
                         * =========================
                         */

                        document.querySelectorAll('.cancel-reply-btn').forEach(button => {

                            button.addEventListener('click', function () {

                                const commentId = this.dataset.commentId;

                                const form = document.getElementById(
                                    `reply-form-${commentId}`
                                );

                                if (!form) {
                                    return;
                                }

                                form.style.display = 'none';

                                const textarea = form.querySelector('textarea');

                                if (textarea) {
                                    textarea.value = '';
                                }
                            });

                        });


                        /*
                         * =========================
                         * TOGGLE REPLIES
                         * =========================
                         */

                        document.querySelectorAll('.toggle-replies-btn').forEach(button => {

                            button.addEventListener('click', function () {

                                const commentId = this.dataset.commentId;

                                const replies = document.getElementById(
                                    `replies-${commentId}`
                                );

                                if (!replies) {
                                    return;
                                }

                                const isHidden = replies.style.display === 'none';

                                if (isHidden) {

                                    // Tampilkan balasan
                                    replies.style.display = 'block';

                                    this.textContent = 'Sembunyikan balasan';

                                    this.setAttribute(
                                        'aria-expanded',
                                        'true'
                                    );

                                } else {

                                    // Sembunyikan balasan
                                    replies.style.display = 'none';

                                    const replyCount =
                                        replies.querySelectorAll(':scope > .comment-wrapper').length;

                                    this.textContent =
                                        `Lihat ${replyCount} balasan`;

                                    this.setAttribute(
                                        'aria-expanded',
                                        'false'
                                    );
                                }
                            });

                        });

                    });
                </script>

            </section>

        </div>

    </div>
</section>

<?php
/* =====================================================
   ARTICLE RECOMMENDATION
====================================================== */

$combinedArticles = [];

$sources = [
    $model['latestArticles'] ?? [],
    $model['recommendedArticles'] ?? [],
    $model['leastViewedArticles'] ?? [],
];

$shownIds = [
    (int) ($model['article']['id'] ?? 0)
];

foreach ($sources as $articles) {

    foreach ($articles as $article) {

        $articleId = (int) $article['id'];

        // Lewati artikel yang sedang dibuka
        // dan artikel yang sudah masuk list
        if (in_array($articleId, $shownIds, true)) {
            continue;
        }

        $shownIds[] = $articleId;
        $combinedArticles[] = $article;
    }
}

// Maksimal 8 artikel
$combinedArticles = array_slice(
    $combinedArticles,
    0,
    8
);
?>


<?php if (!empty($combinedArticles)): ?>

    <section class="hero related-articles">

        <div class="container">

            <div class="article-detail-wrapper">

                <div class="related-section">

                    <!-- HEADER -->
                    <div class="related-header">

                        <h3>
                            <span class="highlight highlight-yellow">
                                Artikel Lainnya
                            </span>
                        </h3>

                    </div>


                    <!-- GRID -->
                    <div class="grid grid-cols-4 gap-grid-md">

                        <?php foreach ($combinedArticles as $article): ?>

                            <a href="/article/<?= htmlspecialchars($article['slug']) ?>" class="neo-card article-card">

                                <?php if (!empty($article['image'])): ?>

                                    <div class="article-image">
                                        <img src="/uploads/articles/<?= htmlspecialchars($article['image']) ?>"
                                            alt="<?= htmlspecialchars($article['title']) ?>" loading="lazy">
                                    </div>

                                <?php else: ?>

                                    <div class="article-image article-image-placeholder">
                                        <span>No Image</span>
                                    </div>

                                <?php endif; ?>


                                <div class="article-content">

                                    <div class="article-header-box">
                                        <h4 class="article-title">
                                            <?= htmlspecialchars($article['title']) ?>
                                        </h4>
                                    </div>


                                    <div class="article-author-info">

                                        <span class="article-author-name">
                                            <?= htmlspecialchars(
                                                $article['authors'] ?? 'Unknown Author'
                                            ) ?>
                                        </span>

                                        <?php if (!empty($article['created_at'])): ?>

                                            <?php
                                            $displayDate = $article['created_at'];

                                            if (
                                                !empty($article['updated_at'])
                                                && $article['updated_at'] > $article['created_at']
                                            ) {
                                                $displayDate = $article['updated_at'];
                                            }
                                            ?>

                                            <span class="article-date">
                                                <?= date('d M Y', strtotime($displayDate)) ?>
                                            </span>

                                        <?php endif; ?>


                                        <?php if (
                                            isset($article['view_count'])
                                            || isset($article['comment_count'])
                                            || isset($article['like_count'])
                                        ): ?>


                                            <div class="article-stats">

                                                <!-- View Count -->
                                                <span class="stat-item" title="Dilihat"
                                                    style="display: inline-flex; align-items: center; gap: 0.25rem;">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                        <path
                                                            d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0" />
                                                        <circle cx="12" cy="12" r="3" />
                                                    </svg>

                                                    <?= number_format(
                                                        (int) ($article['view_count'] ?? 0)
                                                    ) ?>
                                                </span>


                                                <!-- Like Count -->
                                                <span class="stat-item" title="Disukai"
                                                    style="display: inline-flex; align-items: center; gap: 0.25rem;">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                        <path
                                                            d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z" />
                                                    </svg>

                                                    <?= number_format(
                                                        (int) ($article['like_count'] ?? 0)
                                                    ) ?>
                                                </span>


                                                <!-- Comment Count -->
                                                <span class="stat-item" title="Komentar"
                                                    style="display: inline-flex; align-items: center; gap: 0.25rem;">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2-2z" />
                                                    </svg>

                                                    <?= number_format(
                                                        (int) ($article['comment_count'] ?? 0)
                                                    ) ?>
                                                </span>

                                            </div>

                                        <?php endif; ?>

                                    </div>

                                </div>

                            </a>

                        <?php endforeach; ?>

                    </div>

                </div>

            </div>

        </div>

    </section>

<?php endif; ?>
<style>
    /* =========================================================
    1. ARTICLE CARD (untuk section "Artikel Lainnya")
    ========================================================= */

    .neo-card.article-card {
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

    .neo-card.article-card:hover {
        transform: translateY(-4px);
        box-shadow: 6px 6px 0 var(--dark);
    }


    /* =========================
   IMAGE
========================= */

    .article-card .article-image {
        width: 100%;
        aspect-ratio: 16 / 9;

        overflow: hidden;
        flex-shrink: 0;

        margin: 0;
        padding: 0;
    }

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

    .article-card .article-image-placeholder {
        display: flex;
        align-items: center;
        justify-content: center;

        background: #f0f0f0;
        color: #999;

        font-size: 0.85rem;
        font-weight: 700;
    }


    /* =========================
   CONTENT
========================= */

    .article-card .article-content {
        padding-top: 20px;
    }

    .article-card .article-header-box {
        padding-left: 1rem;
        padding-right: 1rem;
    }

    .article-card .article-title {
        margin: 0;

        font-size: 1rem;
        font-weight: 800;
        color: var(--dark);
        line-height: 1.3;
    }


    /* =========================
   AUTHOR INFO
========================= */

    .article-card .article-author-info {
        margin-top: 0.5rem;

        padding-left: 1rem;
        padding-right: 1rem;
        padding-bottom: 1rem;

        display: flex;
        flex-direction: column;

        gap: 0.25rem;
    }

    .article-card .article-author-name {
        margin: 0;

        font-size: 0.8rem;
        color: var(--dark);
    }

    .article-card .article-date {
        margin: 0;

        font-size: 0.8rem;
        color: var(--dark);
    }


    /* =========================================================
   2. SECTION: ARTIKEL LAINNYA
========================================================= */

    .related-articles {
        padding-top: 0px;
        padding-bottom: 80px;
    }

    .related-section {
        margin-bottom: 60px;
    }

    .related-section:last-child {
        margin-bottom: 0;
    }

    .related-header {
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 3px solid var(--dark);
    }

    .related-header h3 {
        margin: 0 0 6px 0;
        font-size: 1.6rem;
        font-weight: 800;
    }

    .related-subtitle {
        margin: 0;
        font-size: 0.9rem;
        color: #666;
        font-weight: 500;
    }

    .article-view-count {
        font-size: 0.8rem;
        color: #666;
        font-weight: 600;
    }

    .article-stats {
        font-size: 0.8rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-top: 0.3rem;
    }


    /* =========================================================
   3. ARTICLE DETAIL
========================================================= */

    .article-detail {
        padding-top: 140px;
    }

    .article-detail-wrapper {
        width: 100%;
        max-width: 900px;
        margin: 0 auto;
    }

    .article-view {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        color: var(--dark);
    }

    .article-view svg {
        width: 16px;
        height: 16px;
    }


    /* =========================
   TAGS
========================= */

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
   ARTICLE DETAIL CARD
========================= */

    .article-detail-card {
        padding: 0;
        overflow: hidden;
    }


    /* =========================
   META
========================= */

    .article-meta {
        display: flex;
        align-items: center;
        flex-wrap: wrap;

        gap: 1rem;

        margin-top: 1rem;
        margin-bottom: 1.5rem;

        font-size: 0.85rem;
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

    .article-date {
        font-size: 0.95rem;
        font-weight: 600;
    }


    /* =========================================================
   4. IMAGE SLIDER
========================================================= */

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


    /* =========================================================
   5. ARTICLE CONTENT / BODY
========================================================= */

    .article-detail-content {
        padding: 40px;
    }

    .article-detail-content h4 {
        margin: 0 0 20px 0;
        font-size: 1.8rem;
        font-weight: 800;
        line-height: 1.3;
    }

    .article-divider {
        margin-bottom: 30px;
    }

    .article-body {
        font-size: 1.1rem;
        line-height: 1.8;
    }

    .article-body p {
        margin-bottom: 20px;

        width: 100% !important;
        max-width: 100% !important;
    }

    .article-body img {
        max-width: 100%;
        height: auto;
    }


    /* =========================================================
   6. ARTICLE ACTIONS (Share & Like)
========================================================= */

    .article-actions {
        display: flex;
        align-items: center;
        justify-content: space-between;

        margin: 24px 0;
        padding: 14px 18px;

        border: 2px solid var(--dark);
        background: #fff;
    }

    .article-action-btn {
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

    .article-action-btn:hover {
        transform: translate(-2px, -2px);
        box-shadow: 3px 3px 0 var(--dark);
    }

    .article-action-btn:active {
        transform: translate(0, 0);
        box-shadow: none;
    }

    .article-like-form {
        margin: 0;
    }

    .article-like-btn.liked {
        background: var(--primary);
        color: #fff;
    }

    .article-like-btn.liked svg {
        fill: currentColor;
    }


    /* =========================================================
   7. COMMENTS
========================================================= */

    .comments-section {
        margin-top: 40px;
    }


    /* =========================
   COMMENTS HEADER
========================= */

    .comments-header {
        margin-bottom: 20px;
    }

    .comments-header h4 {
        display: flex;
        align-items: center;
        gap: 10px;

        margin: 0;

        font-size: 1.5rem;
        font-weight: 800;
    }

    .comments-header .highlight {
        display: inline-flex;
        align-items: center;
        justify-content: center;

        min-width: 32px;
        height: 32px;

        padding: 0 8px;

        font-size: 0.9rem;
        font-weight: 800;

        color: var(--dark);

        border: 2px solid var(--dark);
        box-shadow: 3px 3px 0 var(--dark);
    }


    /* =========================
   COMMENT FORM
========================= */

    .comment-form {
        display: flex;
        flex-direction: column;
        gap: 15px;

        margin-bottom: 30px;
        padding: 20px;

        background-color: var(--light);

        border: var(--border-width-bold) solid var(--dark);
        box-shadow:
            var(--shadow-offset) var(--shadow-offset) 0 var(--dark);
    }

    .comment-form textarea {
        width: 100%;
        min-height: 120px;

        padding: 15px;

        resize: vertical;

        background-color: #fff;

        border: var(--border-width) solid var(--dark);

        outline: none;

        font-family: inherit;
        font-size: 0.95rem;
        line-height: 1.6;

        transition: box-shadow 0.15s ease;
    }

    .comment-form textarea:focus {
        box-shadow: 4px 4px 0 var(--dark);
    }

    .comment-form textarea::placeholder {
        color: #777;
    }


    /* =========================
   COMMENTS LIST
========================= */

    .comments-list {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }


    /* =========================
   COMMENT ITEM
========================= */

    .comment-item {
        position: relative;

        padding: 20px;

        background-color: #fff;

        border: var(--border-width-bold) solid var(--dark);
        box-shadow:
            var(--shadow-offset) var(--shadow-offset) 0 var(--dark);

        transition:
            transform 0.15s ease,
            box-shadow 0.15s ease;
    }

    .comment-item:hover {
        transform: translate(-2px, -2px);
        box-shadow:
            calc(var(--shadow-offset) + 2px) calc(var(--shadow-offset) + 2px) 0 var(--dark);
    }


    /* =========================
   COMMENT USER
========================= */

    .comment-user {
        display: flex;
        align-items: center;

        gap: 12px;

        margin-bottom: 15px;
    }

    .comment-user>div:last-child {
        display: flex;
        flex-direction: column;

        gap: 3px;
    }

    .comment-user strong {
        font-size: 0.95rem;
        font-weight: 800;
    }

    .comment-user small {
        color: #666;

        font-size: 0.75rem;
        font-weight: 500;
    }


    /* =========================
   COMMENT AVATAR
========================= */

    .comment-avatar {
        flex-shrink: 0;

        width: 42px;
        height: 42px;

        border-radius: 50%;

        object-fit: cover;

        border: 2px solid var(--dark);
    }

    .comment-avatar-placeholder {
        display: flex;
        align-items: center;
        justify-content: center;

        background-color: var(--primary);
        color: #fff;

        font-size: 1rem;
        font-weight: 800;
    }


    /* =========================
   COMMENT CONTENT
========================= */

    .comment-content {
        margin-left: 54px;
        margin-bottom: 18px;

        color: var(--dark);

        font-size: 0.95rem;
        line-height: 1.7;

        overflow-wrap: anywhere;
    }


    /* =========================
   COMMENT ACTIONS
========================= */

    .comment-actions {
        display: flex;
        align-items: center;
        flex-wrap: wrap;

        gap: 8px;

        margin-left: 54px;
    }

    .comment-actions form {
        margin: 0;
    }

    .comment-actions button,
    .comment-actions>span {
        display: inline-flex;
        align-items: center;
        justify-content: center;

        min-height: 34px;

        padding: 6px 12px;

        background-color: #fff;

        border: 2px solid var(--dark);
        color: var(--dark);

        font-family: inherit;
        font-size: 0.8rem;
        font-weight: 700;

        cursor: pointer;

        transition:
            transform 0.1s ease,
            box-shadow 0.1s ease,
            background-color 0.1s ease;
    }

    .comment-actions button:hover {
        background-color: #f5f5f5;

        transform: translate(-1px, -1px);
        box-shadow: 2px 2px 0 var(--dark);
    }

    .comment-actions button:active {
        transform: translate(2px, 2px);
        box-shadow: none;
    }

    .comment-actions form:first-child button {
        gap: 5px;
    }

    .comment-actions form:first-child button:hover {
        background-color: #fff3f3;
    }

    .comment-actions form:last-child button {
        font-size: 0.75rem;
    }

    .comment-actions form:last-child button:hover {
        background-color: #ffe5e5;
    }


    /* =========================
   NO COMMENTS
========================= */

    .no-comments {
        padding: 30px;

        text-align: center;

        background-color: #fff;

        border: var(--border-width-bold) dashed var(--dark);
    }

    .no-comments p {
        margin: 0;

        color: #666;

        font-size: 0.9rem;
        font-weight: 600;
    }


    /* =========================
   LOGIN MESSAGE
========================= */

    .comment-login-message {
        margin-bottom: 30px;

        padding: 18px 20px;

        background-color: #fff8d6;

        border: var(--border-width-bold) solid var(--dark);
        box-shadow:
            var(--shadow-offset) var(--shadow-offset) 0 var(--dark);
    }

    .comment-login-message p {
        margin: 0;

        font-size: 0.9rem;
        font-weight: 600;
    }

    .comment-login-message a {
        color: var(--dark);

        font-weight: 800;

        text-decoration: underline;
    }

    .comment-login-message a:hover {
        color: var(--primary);
    }


    /* =========================================================
   8. REPLY FORM
========================================================= */

    .reply-form {
        margin-top: 16px;
        padding: 16px;

        border: 3px solid #111;
        background: #f5f5f5;

        box-sizing: border-box;
    }

    .reply-form textarea {
        display: block;

        width: 100%;
        min-height: 90px;

        padding: 12px;

        border: 3px solid #111;
        background: #fff;

        box-sizing: border-box;

        font-family: inherit;
        font-size: 16px;
        line-height: 1.5;

        resize: vertical;

        outline: none;
    }

    .reply-form textarea:focus {
        box-shadow: 4px 4px 0 #111;
    }

    .reply-form-actions {
        display: flex;
        align-items: center;
        gap: 10px;

        margin-top: 12px;
    }

    .reply-submit-btn,
    .cancel-reply-btn {
        min-height: 42px;

        padding: 8px 16px;

        border: 3px solid #111;
        box-shadow: 4px 4px 0 #111;

        font-family: inherit;
        font-size: 15px;
        font-weight: 700;

        cursor: pointer;

        transition:
            transform 0.15s ease,
            box-shadow 0.15s ease,
            background 0.15s ease;
    }

    .reply-submit-btn {
        background: #16a34a;
        color: #fff;
    }

    .reply-submit-btn:hover {
        transform: translate(2px, 2px);
        box-shadow: 2px 2px 0 #111;
    }

    .cancel-reply-btn {
        background: #fff;
        color: #111;
    }

    .cancel-reply-btn:hover {
        background: #eee;

        transform: translate(2px, 2px);
        box-shadow: 2px 2px 0 #111;
    }

    .toggle-replies-btn {
        margin-top: 14px;

        padding: 6px 0;

        border: none;
        background: transparent;

        color: #111;

        font-family: inherit;
        font-size: 15px;
        font-weight: 700;

        cursor: pointer;
    }

    .toggle-replies-btn:hover {
        text-decoration: underline;
    }


    /* =========================================================
   9. RESPONSIVE
========================================================= */


    /* =========================
   TABLET — max 992px
========================= */

    @media (max-width: 992px) {

        .related-articles .grid.grid-cols-4 {
            grid-template-columns: repeat(2, 1fr) !important;
        }

         .article-card .article-title {
            font-size: 1.3rem;
        }

    }


    /* =========================
   TABLET — max 768px
========================= */

    @media (max-width: 768px) {

        /* ARTICLE CARD */
        .article-card .article-image {
            aspect-ratio: 16 / 9;
        }

        /* RELATED ARTICLES */
        .related-articles .grid.grid-cols-4 {
            grid-template-columns: repeat(2, 1fr) !important;
        }

        /* ARTICLE DETAIL */
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

        /* COMMENTS */
        .comments-section {
            margin-top: 30px;
        }

        .comment-item {
            padding: 16px;
        }

        .comment-content,
        .comment-actions {
            margin-left: 0;
        }

        .article-card .article-title {
            font-size: 1.2rem;
        }

    }


    /* =========================
   MOBILE — max 576px
========================= */

    @media (max-width: 576px) {

        /* RELATED ARTICLES */
        .related-articles {
            padding-top: 40px;
            padding-bottom: 60px;
        }

        .related-section {
            margin-bottom: 40px;
        }

        .related-header h3 {
            font-size: 1.3rem;
        }

        .related-articles .grid.grid-cols-4 {
            grid-template-columns: 1fr !important;
        }

        /* ARTICLE DETAIL */
        .article-detail-slider {
            height: 220px;
        }

        .article-detail-content {
            padding: 20px;
        }

        .article-detail-content h4 {
            font-size: 1.5rem;
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

        /* COMMENTS */
        .comments-header h4 {
            font-size: 1.3rem;
        }

        .comments-header .highlight {
            min-width: 28px;
            height: 28px;
            font-size: 0.8rem;
        }

        .comment-form {
            padding: 15px;
        }

        .comment-item {
            padding: 15px;
        }

        .comment-avatar {
            width: 38px;
            height: 38px;
        }

        .comment-user {
            gap: 10px;
        }

        .comment-content {
            font-size: 0.9rem;
            line-height: 1.6;
        }

        .comment-actions button,
        .comment-actions>span {
            min-height: 32px;

            padding: 5px 10px;

            font-size: 0.75rem;
        }

        .article-card .article-title {
            font-size: 1.2rem;
        }

    }


    /* =========================
   SMALL MOBILE — max 480px
========================= */

    @media (max-width: 480px) {

        .article-card .article-title {
            font-size: 1.1rem;
        }

    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function () {

        const shareButton = document.getElementById('article-share-btn');

        if (!shareButton) {
            return;
        }

        shareButton.addEventListener('click', async function () {

            const shareData = {
                title: <?= json_encode(
                    $model['article']['title'] ?? 'Artikel'
                ) ?>,
                text: 'Baca artikel ini',
                url: window.location.href
            };

            /*
             * Browser mendukung Web Share API
             */
            if (navigator.share) {

                try {

                    await navigator.share(shareData);

                } catch (error) {

                    // User membatalkan share.
                    // Tidak perlu melakukan apa-apa.

                }

                return;
            }


            /*
             * Browser tidak mendukung Web Share API
             * → copy URL
             */
            try {

                await navigator.clipboard.writeText(
                    window.location.href
                );

                const originalText =
                    shareButton.querySelector('span').textContent;

                shareButton.querySelector('span').textContent =
                    'Link disalin';

                setTimeout(function () {

                    shareButton.querySelector('span').textContent =
                        originalText;

                }, 2000);

            } catch (error) {

                alert(
                    'Silakan salin URL halaman ini secara manual.'
                );

            }

        });

    });
</script>
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