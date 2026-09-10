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
                <?php if (($_SESSION['login'] ?? false) === true): ?>

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
                            <a href="/login">login</a>
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
                                        <img src="<?= htmlspecialchars($comment['img']) ?>"
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

                                    <?php if (($_SESSION['login'] ?? false) === true): ?>

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
                                            === (int) $_SESSION['user_id']
                                        ): ?>

                                            <form action="/comment/delete" method="POST">
                                                <input type="hidden" name="comment_id" value="<?= (int) $comment['id'] ?>">

                                                <button type="submit">
                                                    Hapus
                                                </button>
                                            </form>

                                        <?php endif; ?>

                                    <?php else: ?>

                                        <span>
                                            🤍 <?= (int) $comment['like_count'] ?>
                                        </span>

                                        <a href="/login">
                                            Balas
                                        </a>

                                    <?php endif; ?>

                                </div>

                                <!-- Reply Form -->
                                <?php if (($_SESSION['login'] ?? false) === true): ?>

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

    /* =========================
   COMMENTS
========================= */

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

        gap: 8px;

        margin-left: 54px;

        flex-wrap: wrap;
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


    /* =========================
   LIKE BUTTON
========================= */

    .comment-actions form:first-child button {
        gap: 5px;
    }

    .comment-actions form:first-child button:hover {
        background-color: #fff3f3;
    }


    /* =========================
   DELETE BUTTON
========================= */

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


    /* =========================
   TABLET
========================= */

    @media (max-width: 768px) {

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

    }


    /* =========================
   MOBILE
========================= */

    @media (max-width: 576px) {

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

    }

    /* =========================
   REPLY FORM
========================= */

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

        font-family: inherit;
        font-size: 15px;
        font-weight: 700;

        cursor: pointer;
    }

    /* Kirim */
    .reply-submit-btn {
        background: #16a34a;
        color: #fff;

        box-shadow: 4px 4px 0 #111;
    }

    .reply-submit-btn:hover {
        transform: translate(2px, 2px);
        box-shadow: 2px 2px 0 #111;
    }

    /* Batal */
    .cancel-reply-btn {
        background: #fff;
        color: #111;
    }

    .cancel-reply-btn:hover {
        background: #eee;
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