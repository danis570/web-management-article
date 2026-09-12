<?php

$flashMessage = $_SESSION['flash_message'] ?? null;
unset($_SESSION['flash_message']);

$tags = $model['tags'] ?? [];
?>

<!-- =========================
     TAG MANAGEMENT
========================= -->

<section class="hero">
    <div class="container">

        <!-- Flash Message -->
        <?php if ($flashMessage): ?>

            <div class="neo-alert mb-md">
                <?= htmlspecialchars($flashMessage) ?>
            </div>

        <?php endif; ?>


        <!-- Error -->
        <?php if (!empty($model['error'])): ?>

            <div class="neo-alert neo-alert-error mb-md">
                <?= htmlspecialchars($model['error']) ?>
            </div>

        <?php endif; ?>

        <!-- Header -->
        <div class="article-header" style="display: flex; justify-content: space-between; align-items: center;">
            <h3>
                Semua <span class="highlight highlight-yellow">Tag</span>
            </h3>

            <div class="search-container" style="display: flex; align-items: center; gap: 1rem;">
                <button type="button" class="neo-btn neo-btn-primary neo-btn-sm" onclick="openTagModal('add')">
                    Add
                </button>

                <div class="article-search">
                    <input type="search" id="tag-search" placeholder="Cari tag...">
                </div>
            </div>
        </div>

        <!-- Tag List -->
        <?php if (!empty($tags)): ?>

            <div class="grid grid-cols-4 gap-grid-md" id="tag-list">

                <?php foreach ($tags as $tag): ?>

                    <?php
                    /*
                     * TagRepository menggunakan FETCH_CLASS,
                     * sehingga $tag adalah object Tag.
                     */
                    $tagId = $tag->id ?? null;
                    $tagName = $tag->name ?? '';
                    $tagSlug = $tag->slug ?? '';
                    ?>

                    <div class="neo-card tag-card" data-tag-name="<?= htmlspecialchars(strtolower($tagName)) ?>">

                        <!-- Tag Name -->
                        <div class="tag-card-content">
                            <h3 class="tag-name">
                                #<?= htmlspecialchars($tagName) ?>
                            </h3>
                        </div>


                        <!-- Actions -->
                        <div class="tag-actions">

                            <!-- Edit -->
                            <button type="button" class="neo-btn neo-btn-small" onclick='openTagModal(
                                    "edit",
                                    <?= json_encode([
                                        'id' => $tagId,
                                        'name' => $tagName,
                                        'slug' => $tagSlug
                                    ], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>
                                )'>
                                Edit
                            </button>


                            <!-- Delete -->
                            <button type="button" class="neo-btn neo-btn-small neo-btn-danger" onclick="openDeleteModal(
                                    <?= (int) $tagId ?>,
                                    <?= htmlspecialchars(
                                        json_encode($tagName),
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>
                                )">
                                Hapus
                            </button>

                        </div>

                    </div>

                <?php endforeach; ?>

            </div>


            <!-- Empty Search Result -->
            <div id="tag-empty-search" class="neo-box text-center mt-lg" style="display: none;">
                <p>Tag tidak ditemukan.</p>
            </div>


        <?php else: ?>

            <!-- No Tags -->
            <div class="neo-box text-center">

                <h3>Belum ada tag</h3>

                <p class="mb-md">
                    Belum ada tag yang tersedia.
                </p>

                <button type="button" class="neo-btn neo-btn-primary" onclick="openTagModal('add')">
                    + Tambah Tag
                </button>

            </div>

        <?php endif; ?>

    </div>
</section>


<!-- =========================
     ADD / EDIT TAG MODAL
========================= -->

<div id="tag-modal" class="modal-overlay" style="display: none;">

    <div class="modal-box">

        <!-- Modal Header -->
        <div class="modal-header">

            <h2 id="tag-modal-title">
                Tambah Tag
            </h2>

            <button type="button" class="modal-close" onclick="closeTagModal()" aria-label="Close">
                &times;
            </button>

        </div>


        <!-- Form -->
        <form id="tag-form" method="POST" action="/tag/add">

            <!-- ID -->
            <input type="hidden" name="id" id="tag-id" value="">


            <!-- Name -->
            <div class="form-group">

                <label for="tag-name">
                    Nama Tag
                </label>

                <input type="text" name="name" id="tag-name" class="neo-input" placeholder="Contoh: Organisasi"
                    maxlength="100" required>

            </div>


            <!-- Slug -->
            <div class="form-group">

                <label for="tag-slug">
                    Slug
                </label>

                <input type="text" name="slug" id="tag-slug" class="neo-input" placeholder="contoh: organisasi"
                    maxlength="255" required>

                <small class="form-help">
                    Slug digunakan pada URL tag.
                </small>

            </div>


            <!-- Buttons -->
            <div class="modal-actions">

                <button type="button" class="neo-btn" onclick="closeTagModal()">
                    Batal
                </button>

                <button type="submit" class="neo-btn neo-btn-primary" id="tag-submit-button">
                    Simpan
                </button>

            </div>

        </form>

    </div>

</div>


<!-- =========================
     DELETE MODAL
========================= -->

<div id="delete-modal" class="modal-overlay" style="display: none;">

    <div class="modal-box modal-box-small">

        <!-- Header -->
        <div class="modal-header">

            <h2>
                Hapus Tag
            </h2>

            <button type="button" class="modal-close" onclick="closeDeleteModal()" aria-label="Close">
                &times;
            </button>

        </div>


        <!-- Content -->
        <div class="modal-content">

            <p>
                Apakah kamu yakin ingin menghapus tag
                <strong id="delete-tag-name"></strong>?
            </p>

            <p class="form-help">
                Tag akan dihapus dari daftar tag.
            </p>

        </div>


        <!-- Delete Form -->
        <form method="POST" action="/tag/delete">

            <input type="hidden" name="id" id="delete-tag-id">

            <div class="modal-actions">

                <button type="button" class="neo-btn" onclick="closeDeleteModal()">
                    Batal
                </button>

                <button type="submit" class="neo-btn neo-btn-danger">
                    Ya, Hapus
                </button>

            </div>

        </form>

    </div>

</div>


<!-- =========================
     CSS
========================= -->

<style>
    #tag-list {
        display: grid !important;
        column-gap: 1.5rem !important;
        row-gap: 1.5rem !important;
        align-items: start !important;
        margin-top: 3.5rem;
    }

    #tag-list .tag-card {
        height: auto !important;
        min-height: unset;
        margin-bottom: 1.5rem;
        box-sizing: border-box;
    }

    /* =========================
       PAGE HEADER
    ========================= */

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .page-header h1 {
        margin: 0;
    }

    .page-description {
        margin-top: .5rem;
        color: #666;
    }


    /* =========================
       SEARCH
    ========================= */

    .tag-search-box {
        padding: 1rem;
    }


    /* =========================
       TAG CARD
    ========================= */

    .tag-card {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-height: 150px;
        padding: 1.5rem;
        box-sizing: border-box;
    }

    .tag-card-content {
        min-width: 0;
    }

    .tag-name {
        margin: 0;
        font-size: 1.25rem;
        font-weight: 700;
        overflow-wrap: anywhere;
    }

    .tag-slug {
        margin-top: .75rem;
        color: #666;
        font-size: .9rem;
        overflow-wrap: anywhere;
    }


    /* =========================
       ACTIONS
    ========================= */

    .tag-actions {
        display: flex;
        gap: .75rem;
        margin-top: 1.5rem;
    }

    .neo-btn-small {
        padding: .5rem .8rem;
        font-size: .85rem;
    }


    /* =========================
       BUTTON
    ========================= */

    .neo-btn-danger {
        background-color: #ef4444;
        color: #fff;
    }

    .neo-btn-danger:hover {
        background-color: #dc2626;
    }


    /* =========================
       ALERT
    ========================= */

    .neo-alert {
        padding: 1rem 1.25rem;
        border: 3px solid #111;
        background-color: #fef08a;
        font-weight: 600;
        box-shadow: 5px 5px 0 #111;
    }

    .neo-alert-error {
        background-color: #fecaca;
    }


    /* =========================
       FORM
    ========================= */

    .form-group {
        margin-bottom: 1.25rem;
    }

    .form-group label {
        display: block;
        margin-bottom: .5rem;
        font-weight: 700;
    }

    .form-help {
        display: block;
        margin-top: .4rem;
        color: #666;
        font-size: .85rem;
    }

    .neo-input {
        width: 100%;
        padding: .8rem 1rem;
        border: 3px solid #111;
        outline: none;
        background: #fff;
        box-sizing: border-box;
        font-family: inherit;
    }

    .neo-input:focus {
        box-shadow: 4px 4px 0 #111;
    }


    /* =========================
       MODAL
    ========================= */

    .modal-overlay {
        position: fixed;
        inset: 0;
        z-index: 9999;

        display: flex;
        justify-content: center;
        align-items: center;

        padding: 1rem;

        background: rgba(0, 0, 0, .65);
        box-sizing: border-box;
    }

    .modal-box {
        width: 100%;
        max-width: 550px;

        background: #fff;
        border: 4px solid #111;
        box-shadow: 8px 8px 0 #111;

        padding: 1.5rem;
        box-sizing: border-box;
    }

    .modal-box-small {
        max-width: 450px;
    }


    /* =========================
       MODAL HEADER
    ========================= */

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;

        margin-bottom: 1.5rem;
    }

    .modal-header h2 {
        margin: 0;
    }

    .modal-close {
        border: 3px solid #111;
        background: #fff;

        width: 40px;
        height: 40px;

        font-size: 1.5rem;
        font-weight: 700;

        cursor: pointer;
    }

    .modal-close:hover {
        background: #f3f4f6;
    }


    /* =========================
       MODAL ACTIONS
    ========================= */

    .modal-actions {
        display: flex;
        justify-content: flex-end;
        gap: .75rem;
        margin-top: 1.5rem;
    }


    /* =========================
       RESPONSIVE
    ========================= */

    @media (max-width: 1000px) {

        .grid.grid-cols-4 {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

    }

    @media (max-width: 600px) {

        .page-header {
            flex-direction: column;
            align-items: stretch;
        }

        .page-header .neo-btn {
            width: 100%;
        }

        .grid.grid-cols-4 {
            grid-template-columns: 1fr;
        }

        .modal-box {
            padding: 1.25rem;
        }

        .modal-actions {
            flex-direction: column;
        }

        .modal-actions .neo-btn {
            width: 100%;
        }

    }
</style>


<!-- =========================
     JAVASCRIPT
========================= -->

<script>

    /*
     * =========================
     * ADD / EDIT MODAL
     * =========================
     */

    function openTagModal(mode, tag = null) {

        const modal = document.getElementById('tag-modal');
        const form = document.getElementById('tag-form');

        const title = document.getElementById('tag-modal-title');
        const submitButton = document.getElementById('tag-submit-button');

        const idInput = document.getElementById('tag-id');
        const nameInput = document.getElementById('tag-name');
        const slugInput = document.getElementById('tag-slug');


        /*
         * ADD
         */

        if (mode === 'add') {

            title.textContent = 'Tambah Tag';

            submitButton.textContent = 'Tambah';

            form.action = '/tag/add';

            idInput.value = '';
            nameInput.value = '';
            slugInput.value = '';

        }


        /*
         * EDIT
         */

        if (mode === 'edit' && tag) {

            title.textContent = 'Edit Tag';

            submitButton.textContent = 'Simpan';

            form.action = '/tag/edit';

            idInput.value = tag.id ?? '';
            nameInput.value = tag.name ?? '';
            slugInput.value = tag.slug ?? '';

        }


        modal.style.display = 'flex';

        setTimeout(() => {
            nameInput.focus();
        }, 50);

    }


    function closeTagModal() {

        document.getElementById('tag-modal').style.display = 'none';

    }


    /*
     * =========================
     * DELETE MODAL
     * =========================
     */

    function openDeleteModal(id, name) {

        const modal = document.getElementById('delete-modal');

        const idInput = document.getElementById('delete-tag-id');

        const nameElement = document.getElementById('delete-tag-name');


        idInput.value = id;

        nameElement.textContent = '#' + name;

        modal.style.display = 'flex';

    }


    function closeDeleteModal() {

        document.getElementById('delete-modal').style.display = 'none';

    }


    /*
     * =========================
     * CLOSE MODAL WHEN CLICK
     * OUTSIDE
     * =========================
     */

    document.addEventListener('click', function (event) {

        const tagModal = document.getElementById('tag-modal');

        const deleteModal = document.getElementById('delete-modal');


        if (
            event.target === tagModal
        ) {
            closeTagModal();
        }


        if (
            event.target === deleteModal
        ) {
            closeDeleteModal();
        }

    });


    /*
     * =========================
     * ESCAPE TO CLOSE MODAL
     * =========================
     */

    document.addEventListener('keydown', function (event) {

        if (event.key !== 'Escape') {
            return;
        }

        closeTagModal();
        closeDeleteModal();

    });


    /*
     * =========================
     * AUTO GENERATE SLUG
     * =========================
     */

    const tagNameInput = document.getElementById('tag-name');
    const tagSlugInput = document.getElementById('tag-slug');


    let slugManuallyChanged = false;


    tagSlugInput.addEventListener('input', function () {

        slugManuallyChanged = true;

    });


    tagNameInput.addEventListener('input', function () {

        /*
         * Kalau user belum mengubah slug secara manual,
         * slug otomatis mengikuti nama.
         */

        if (slugManuallyChanged) {
            return;
        }


        let slug = this.value
            .toLowerCase()
            .trim()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/^-+|-+$/g, '');

        tagSlugInput.value = slug;

    });


    /*
     * Reset status slug ketika modal dibuka.
     */

    const originalOpenTagModal = openTagModal;

    openTagModal = function (mode, tag = null) {

        slugManuallyChanged = false;

        originalOpenTagModal(mode, tag);

    };


    /*
     * =========================
     * SEARCH TAG
     * =========================
     */

    const searchInput = document.getElementById('tag-search');

    if (searchInput) {

        searchInput.addEventListener('input', function () {

            const keyword = this.value
                .toLowerCase()
                .trim();

            const cards = document.querySelectorAll(
                '#tag-list .tag-card'
            );

            let visibleCount = 0;


            cards.forEach(function (card) {

                const tagName =
                    card.dataset.tagName ?? '';

                if (tagName.includes(keyword)) {

                    card.style.display = '';

                    visibleCount++;

                } else {

                    card.style.display = 'none';

                }

            });


            const emptySearch =
                document.getElementById('tag-empty-search');


            if (emptySearch) {

                if (visibleCount === 0 && cards.length > 0) {

                    emptySearch.style.display = 'block';

                } else {

                    emptySearch.style.display = 'none';

                }

            }

        });

    }

</script>