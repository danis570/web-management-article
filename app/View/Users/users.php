<section class="hero user-page">
    <div class="container">


        <!-- =====================================================
             HEADER
        ====================================================== -->

        <div class="user-header">

            <h3>
                <span class="highlight highlight-yellow" id="article-title">
                    Pengguna
                </span>
            </h3>


            <!-- =====================================================
                 SEARCH
            ====================================================== -->

            <div class="search-container" role="search">

                <div class="user-search">

                    <input type="search" id="user-search" placeholder="Cari user..." aria-label="Cari user"
                        autocomplete="off">

                </div>

            </div>

        </div>


        <!-- =====================================================
             USER LIST
        ====================================================== -->

        <?php if (!empty($model['users'])): ?>

            <div class="user-grid" id="user-list">


                <?php foreach ($model['users'] as $user): ?>

                    <?php
                    $email = $user['email'] ?? '';

                    $username = $email !== ''
                        ? explode('@', $email)[0]
                        : '';

                    $profile = $model['profiles'][$user['id']] ?? null;

                    ?>

                    <a href="/@<?= urlencode($username) ?>" class="neo-card user-card">


                        <!-- =====================================================
                             IMAGE
                        ====================================================== -->

                        <div class="user-card-image">

                            <?php if ($profile && !empty($profile->img)): ?>

                                <img src="/uploads/users/<?= htmlspecialchars($profile->img) ?>"
                                    alt="<?= htmlspecialchars($profile->name) ?>" loading="lazy">

                            <?php else: ?>

                                <div class="user-card-placeholder">
                                    <?= strtoupper(
                                        substr(
                                            $profile->name ?? $username,
                                            0,
                                            1
                                        )
                                    ) ?>
                                </div>

                            <?php endif; ?>

                        </div>


                        <!-- =====================================================
                             USER CONTENT
                        ====================================================== -->

                        <div class="user-card-content">


                            <!-- =========================
                                 NAME
                            ========================== -->

                            <div class="user-card-header-box">

                                <h2 class="user-card-name">
                                    <?= htmlspecialchars(
                                        $profile->name ?? $username
                                    ) ?>
                                </h2>

                            </div>


                            <!-- =========================
                                 POSITION & USERNAME
                            ========================== -->

                            <div class="user-card-meta">

                                <?php if ($profile && !empty($profile->position)): ?>

                                    <span class="user-card-position">
                                        <?= htmlspecialchars($profile->position) ?>
                                    </span>

                                <?php endif; ?>

                                <?php if ($username !== ''): ?>

                                    <span class="user-card-username">
                                        @<?= htmlspecialchars($username) ?>
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
                    User tidak ditemukan.
                </p>

            </div>


        <?php else: ?>

            <div class="neo-card empty-state">

                <div class="empty-state-icon">
                    👥
                </div>

                <p>
                    Belum ada user.
                </p>

            </div>

        <?php endif; ?>

    </div>
</section>


<style>
    /* =========================================================
   USER HEADER
========================================================= */

    .user-header {

        display: flex;

        align-items: center;

        justify-content: space-between;

        gap: 1.5rem;

        width: 100%;
    }


    .user-header-title {

        margin: 0;

        font-size: 1.8rem;

        font-weight: 900;

        color: var(--dark);

        line-height: 1.2;
    }


    /* =========================================================
   SEARCH
========================================================= */

    .user-header .search-container {

        display: flex;

        align-items: center;

        gap: 1rem;

        flex-shrink: 0;
    }


    .user-search {

        display: flex;

        align-items: center;

        gap: 0.625rem;
    }


    .user-search input {

        width: 250px;

        padding: 0.625rem 0.875rem;

        border: var(--border-width, 3px) solid var(--dark);

        background-color: #ffffff;

        color: var(--dark);

        font-family: inherit;

        font-size: 1rem;

        font-weight: 600;

        outline: none;

        box-shadow:
            var(--shadow-offset, 4px) var(--shadow-offset, 4px) 0 var(--dark);

        transition:
            background-color 0.2s ease,
            transform 0.2s ease,
            box-shadow 0.2s ease;
    }


    .user-search input:focus {

        background-color: #ffffff;

        transform: translate(-2px, -2px);

        box-shadow: 6px 6px 0 var(--dark);
    }


    .user-search input::placeholder {

        color: var(--dark);

        opacity: 0.5;
    }


    /* =========================================================
   USER GRID
========================================================= */

    .user-grid {

        display: grid;

        /*
    Default: 8 kolom di layar besar.
    */

        grid-template-columns: repeat(8, 1fr);

        gap: 1.5rem;

        margin-top: 2rem;

        align-items: start;
    }


    /* =========================================================
   USER CARD
========================================================= */

    .neo-card.user-card {

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

    .user-card .user-card-image {

        width: 100%;

        aspect-ratio: 1 / 1;

        overflow: hidden;

        flex-shrink: 0;

        margin: 0;

        padding: 0;

        background: #f3f4f6;

        border-bottom: var(--border-width, 3px) solid var(--dark);
    }


    /* =========================================================
   IMAGE
========================================================= */

    .user-card .user-card-image img {

        display: block;

        width: 100%;

        height: 100%;

        margin: 0;

        padding: 0;

        border: 0;

        object-fit: cover;

        object-position: center;

        transition: transform 0.4s ease;
    }


    .neo-card.user-card:hover .user-card-image img {

        transform: scale(1.05);
    }


    /* =========================================================
   PLACEHOLDER
========================================================= */

    .user-card .user-card-placeholder {

        width: 100%;

        height: 100%;

        display: flex;

        align-items: center;

        justify-content: center;

        background: var(--yellow);

        color: var(--dark);

        font-size: 3rem;

        font-weight: 900;
    }


    /* =========================================================
   USER CONTENT
========================================================= */

    .user-card .user-card-content {

        /*
    Jarak antara gambar dan teks.
    */

        padding-top: 20px;
    }


    /* =========================================================
   NAME BOX
========================================================= */

    .user-card .user-card-header-box {

        padding-left: 1.5rem;

        padding-right: 1.5rem;
    }


    /* =========================================================
   NAME
========================================================= */

    .user-card .user-card-name {

        margin: 0;

        font-size: 1.2rem;

        font-weight: 800;

        color: var(--dark);

        line-height: 1.3;
    }


    /* =========================================================
   META (POSITION + USERNAME)
========================================================= */

    .user-card .user-card-meta {

        margin-top: 1rem;

        padding-left: 1.5rem;

        padding-right: 1.5rem;

        padding-bottom: 1.5rem;

        display: flex;

        flex-direction: column;

        gap: 0.25rem;
    }


    /* =========================================================
   POSITION
========================================================= */

    .user-card .user-card-position {

        margin: 0;

        font-size: 0.95rem;

        color: var(--dark);
    }


    /* =========================================================
   USERNAME
========================================================= */

    .user-card .user-card-username {

        margin: 0;

        font-size: 0.95rem;

        color: var(--dark);

        opacity: 0.65;
    }


    /* =========================================================
   HOVER
========================================================= */

    .neo-card.user-card:hover {

        transform: translateY(-4px);

        box-shadow: 6px 6px 0px var(--dark);
    }


    /* =========================================================
   SEARCH / HIDDEN
========================================================= */

    .user-card.is-hidden {

        opacity: 0 !important;

        transform: scale(0) !important;

        position: absolute !important;

        pointer-events: none !important;

        transition: all 0.3s ease;
    }


    /* =========================================================
   USER PROFILE
========================================================= */

    .user-profile {

        display: flex;

        align-items: center;

        gap: 2rem;

        padding: 2rem;

        background: #ffffff;

        border: var(--border-width, 3px) solid var(--dark);

        border-radius: 16px;

        box-shadow: 5px 5px 0 var(--dark);
    }


    /* =========================================================
   PROFILE IMAGE
========================================================= */

    .user-profile-image {

        width: 150px;

        height: 150px;

        flex-shrink: 0;

        overflow: hidden;

        border: var(--border-width, 3px) solid var(--dark);

        border-radius: 50%;

        background: #f3f4f6;
    }


    .user-profile-image img {

        width: 100%;

        height: 100%;

        display: block;

        object-fit: cover;
    }


    /* =========================================================
   PROFILE PLACEHOLDER
========================================================= */

    .user-profile-placeholder {

        width: 100%;

        height: 100%;

        display: flex;

        align-items: center;

        justify-content: center;

        background: var(--yellow);

        color: var(--dark);

        font-size: 4rem;

        font-weight: 900;
    }


    /* =========================================================
   PROFILE INFO
========================================================= */

    .user-profile-info {

        min-width: 0;
    }


    .user-profile-name {

        margin: 0;

        font-size: 2rem;

        font-weight: 900;

        line-height: 1.15;
    }


    .user-profile-position {

        margin: .5rem 0 0;

        font-size: 1rem;

        font-weight: 800;
    }


    .user-profile-period {

        margin: .25rem 0 0;

        font-size: .9rem;

        font-weight: 700;

        opacity: .7;
    }


    .user-profile-username {

        margin: .75rem 0 0;

        font-size: .9rem;

        font-weight: 800;

        opacity: .65;
    }


    /* =========================================================
   LATEST POSTS
========================================================= */

    .user-profile-posts {

        margin-top: 3rem;
    }


    .user-profile-posts .section-header {

        margin-bottom: 1.5rem;
    }


    .user-posts-empty {

        width: 100%;
    }


    /* =========================================================
   EMPTY STATE
========================================================= */

    .empty-state {

        padding: 2.5rem 2rem;

        text-align: center;

        font-weight: 800;
    }


    .empty-state .empty-state-icon {

        font-size: 3rem;

        margin-bottom: 1rem;

        line-height: 1;
    }


    /* =========================================================
   RESPONSIVE — 1400px (6 KOLOM)
========================================================= */

    @media (max-width: 1399px) {

        .user-grid {

            grid-template-columns: repeat(6, 1fr);
        }
    }


    /* =========================================================
   RESPONSIVE — 1200px (5 KOLOM)
========================================================= */

    @media (max-width: 1199px) {

        .user-grid {

            grid-template-columns: repeat(5, 1fr);
        }
    }


    /* =========================================================
   RESPONSIVE — 1000px (4 KOLOM)
========================================================= */

    @media (max-width: 999px) {

        .user-grid {

            grid-template-columns: repeat(4, 1fr);

            gap: 1.25rem;
        }
    }


    /* =========================================================
   RESPONSIVE — 768px (4 KOLOM — TETAP)
========================================================= */

    @media (max-width: 767px) {

        .user-header {

            flex-direction: column;

            align-items: flex-start;

            gap: 1rem;
        }


        .user-header .search-container {

            width: 100%;
        }


        .user-search {

            width: 100%;
        }


        .user-search input {

            width: 100%;
        }


        .user-grid {

            /*
        KUNCI: tetap 4 kolom.
        */

            grid-template-columns: repeat(4, 1fr);

            gap: 1rem;
        }


        .user-profile {

            flex-direction: column;

            text-align: center;
        }


        .user-card .user-card-content {

            padding-top: 12px;
        }


        .user-card .user-card-header-box {

            padding-left: 0.75rem;

            padding-right: 0.75rem;
        }


        .user-card .user-card-meta {

            margin-top: 0.5rem;

            padding-left: 0.75rem;

            padding-right: 0.75rem;

            padding-bottom: 0.75rem;

            gap: 0.125rem;
        }


        .user-card .user-card-name {

            font-size: 0.85rem;
        }


        .user-card .user-card-position,
        .user-card .user-card-username {

            font-size: 0.7rem;
        }
    }


    /* =========================================================
   RESPONSIVE — 480px (4 KOLOM — TETAP)
========================================================= */

    @media (max-width: 480px) {

        .user-header-title {

            font-size: 1.5rem;
        }


        .user-grid {

            /*
        Tetap 4 kolom, jangan turun.
        */

            grid-template-columns: repeat(4, 1fr);

            gap: 0.5rem;
        }


        .user-card .user-card-name {

            font-size: 0.75rem;
        }


        .user-card .user-card-content {

            padding-top: 10px;
        }


        .user-card .user-card-header-box {

            padding-left: 0.5rem;

            padding-right: 0.5rem;
        }


        .user-card .user-card-meta {

            margin-top: 0.25rem;

            padding-left: 0.5rem;

            padding-right: 0.5rem;

            padding-bottom: 0.5rem;

            gap: 0.125rem;
        }


        .user-card .user-card-position,
        .user-card .user-card-username {

            font-size: 0.6rem;
        }
    }
</style>


<script>

    document.addEventListener('DOMContentLoaded', function () {


        /* =====================================================
           ELEMENT
        ====================================================== */

        const searchInput =
            document.getElementById('user-search');

        const userList =
            document.getElementById('user-list');

        const noResult =
            document.getElementById('no-result');


        if (!userList) {

            return;
        }


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
           REALTIME SEARCH (DEBOUNCED)
        ====================================================== */

        function setupSearch() {


            if (!searchInput) {

                return;
            }


            let debounceTimer =
                null;


            searchInput.addEventListener(
                'input',
                function () {


                    clearTimeout(debounceTimer);


                    debounceTimer =
                        setTimeout(() => {


                            const keyword =
                                this.value
                                    .toLowerCase()
                                    .trim();


                            const users =
                                userList.querySelectorAll(
                                    '.user-card'
                                );


                            let found =
                                false;


                            /* =========================================
                               FILTER
                            ========================================== */

                            users.forEach(user => {


                                const fullText =
                                    user.textContent
                                        .toLowerCase();


                                if (
                                    keyword === '' ||
                                    fullText.includes(keyword)
                                ) {


                                    user.classList.remove(
                                        'is-hidden'
                                    );


                                    found =
                                        true;


                                } else {


                                    user.classList.add(
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


                        }, 150);
                }
            );

        }


        /* =====================================================
           INITIALIZE
        ====================================================== */

        setupSearch();


    });

</script>