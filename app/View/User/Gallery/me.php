<section class="hero">

    <div class="container">

        <div class="hero-content">

            <?php if (isset($_SESSION['flash_message'])): ?>

                <div class="neo-box bg-success mb-md"
                    style="
                        display: flex;
                        justify-content: space-between;
                        align-items: center;
                        padding: 1rem 1.5rem;
                        background-color: transparent;
                        color: var(--dark);
                        border: 2px solid var(--dark);
                        box-shadow: 4px 4px 0px var(--dark);
                        font-weight: bold;
                        border-radius: 4px;
                    ">

                    <span>
                        <?= htmlspecialchars($_SESSION['flash_message']) ?>
                    </span>

                    <button
                        onclick="this.parentElement.remove()"
                        style="
                            background: none;
                            border: none;
                            color: var(--dark);
                            font-size: 1.2rem;
                            cursor: pointer;
                            font-weight: bold;
                        ">
                        &times;
                    </button>

                </div>

                <?php unset($_SESSION['flash_message']); ?>

            <?php endif; ?>


            <div class="hero-text">

                <div class="article-header"
                    style="
                        display: flex;
                        justify-content: space-between;
                        align-items: center;
                    ">

                    <h3>
                        <span class="highlight highlight-yellow">
                            Gallery Saya
                        </span>
                    </h3>


                    <!-- Tombol Add + Search -->
                    <div class="search-container"
                        style="
                            display: flex;
                            align-items: center;
                            gap: 1rem;
                        ">

                        <a href="/gallery/add"
                            class="neo-btn neo-btn-primary neo-btn-sm">
                            Add
                        </a>

                        <div class="article-search">

                            <input
                                type="search"
                                id="gallery-search"
                                placeholder="Cari gallery...">

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- =========================
         GALLERY LIST
    ========================== -->

    <div class="container" style="margin-top: 3.5rem;">

        <div class="component-example">


            <?php if (isset($model['galleries']) && !empty($model['galleries'])): ?>


                <div
                    class="grid grid-cols-4 gap-grid-md mt-md"
                    id="gallery-list"
                    style="align-items: start;"
                >


                    <?php foreach ($model['galleries'] as $gallery): ?>


                        <div class="neo-card gallery-card">


                            <!-- =========================
                                 GALLERY HEADER
                            ========================== -->

                            <div class="gallery-header-box">

                                <h4 class="gallery-title">

                                    <a
                                        href="/gallery/<?= urlencode($gallery->slug) ?>"
                                        style="
                                            text-decoration: none;
                                            color: inherit;
                                        "
                                        onmouseover="this.style.color='var(--primary)'"
                                        onmouseout="this.style.color='inherit'"
                                    >

                                        <?= htmlspecialchars(
                                            $gallery->caption ?? 'Untitled Gallery'
                                        ) ?>

                                    </a>

                                </h4>

                            </div>


                            <!-- =========================
                                 GALLERY DETAILS
                            ========================== -->

                            <div class="gallery-details">



            


                                <!-- Created -->

                                <div class="gallery-info-group">

                                    <p class="gallery-label">
                                        Dibuat:
                                    </p>

                                    <p class="gallery-value">

                                        <?= htmlspecialchars(
                                            $gallery->createdAt
                                        ) ?>

                                    </p>

                                </div>


                                <!-- =========================
                                     ACTION
                                ========================== -->

                                <div
                                    class="gallery-actions"
                                    style="
                                        display: flex;
                                        gap: 0.5rem;
                                        margin-top: 0.75rem;
                                    "
                                >


                                    <!-- Edit -->

                                    <a
                                        href="/gallery/edit/<?= urlencode($gallery->slug) ?>"
                                        class="neo-btn neo-btn-sm"
                                        style="
                                            flex: 1;
                                            width: 100%;
                                            background-color: var(--yellow-light);
                                            font-size: 0.8rem;
                                            padding: 0.5rem;
                                            text-align: center;
                                            text-decoration: none;
                                        "
                                    >

                                        Edit

                                    </a>


                                    <!-- Delete -->

                                    <form
                                        id="form-delete-gallery-<?= (int) $gallery->id ?>"
                                        action="/gallery/delete/<?= (int) $gallery->id ?>"
                                        method="post"
                                        style="flex: 1; margin: 0;"
                                    >

                                        <button
                                            type="button"
                                            class="neo-btn neo-btn-sm"
                                            style="
                                                width: 100%;
                                                background-color: #ff5757;
                                                color: #fff;
                                                font-size: 0.8rem;
                                                padding: 0.5rem;
                                            "
                                            onclick="openDeleteGalleryModal(
                                                '<?= htmlspecialchars(
                                                    $gallery->caption ?? 'Untitled Gallery',
                                                    ENT_QUOTES
                                                ) ?>',
                                                'form-delete-gallery-<?= (int) $gallery->id ?>'
                                            )"
                                        >

                                            Delete

                                        </button>

                                    </form>


                                </div>

                            </div>


                        </div>


                    <?php endforeach; ?>


                </div>


                <!-- =========================
                     DELETE GALLERY MODAL
                ========================== -->

                <div
                    id="deleteGalleryModalOverlay"
                    class="neo-modal-overlay"
                    style="display: none;"
                >

                    <div class="neo-modal-box">


                        <div class="neo-modal-header">

                            PERHATIAN!

                        </div>


                        <div class="neo-modal-body">

                            <p>
                                Apakah Anda yakin ingin menghapus gallery:
                            </p>

                            <strong id="deleteGalleryTargetCaption"></strong>

                            <p style="margin-top: 0.75rem;">
                                Gallery yang dihapus tidak dapat ditampilkan lagi.
                            </p>

                        </div>


                        <div class="neo-modal-footer">


                            <button
                                type="button"
                                class="neo-btn-modal btn-cancel"
                                onclick="closeDeleteGalleryModal()"
                            >

                                Batal

                            </button>


                            <button
                                type="button"
                                class="neo-btn-modal btn-confirm"
                                id="deleteGallerySubmitBtn"
                            >

                                Ya, Hapus!

                            </button>


                        </div>


                    </div>

                </div>


                <!-- =========================
                     NO SEARCH RESULT
                ========================== -->

                <div
                    id="no-result"
                    style="display: none;"
                    class="text-center mt-md"
                >

                    <p>
                        Gallery tidak ditemukan.
                    </p>

                </div>


            <?php else: ?>


                <div class="text-center mt-md">

                    <p>
                        <?= htmlspecialchars(
                            $model['emptyGallery']
                            ?? 'Belum ada gallery.'
                        ) ?>
                    </p>

                </div>


            <?php endif; ?>


        </div>

    </div>

</section>


<style>

/* =========================================
   GALLERY CARD
========================================= */

#gallery-list {
    row-gap: 1.5rem !important;
}


#gallery-list .gallery-card {
    margin-bottom: 1.5rem;
}


.neo-card.gallery-card {

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
   GALLERY TITLE
========================================= */

.gallery-card .gallery-title {

    margin: 0;

    font-size: 1.2rem;

    font-weight: 800;

    color: var(--dark);

    line-height: 1.3;

}


/* =========================================
   GALLERY DETAILS
========================================= */

.gallery-card .gallery-details {

    display: flex;

    flex-direction: column;

    margin-top: 1rem;

}


.gallery-info-group {

    margin-bottom: 0.6rem;

}


.gallery-info-group:last-child {

    margin-bottom: 0;

}


/* =========================================
   LABEL
========================================= */

.gallery-card .gallery-label {

    margin: 0;

    font-size: 0.85rem;

    font-weight: 700;

    color: var(--dark);

}


/* =========================================
   VALUE
========================================= */

.gallery-card .gallery-value {

    margin: 0.15rem 0 0;

    font-size: 0.85rem;

    line-height: 1.4;

    color: #555555;

    overflow-wrap: anywhere;

}


/* =========================================
   ACTION
========================================= */

.gallery-actions {

    display: flex;

    gap: 0.5rem;

    margin-top: 0.75rem;

}


/* =========================================
   HOVER
========================================= */

.neo-card.gallery-card:hover {

    transform: translateY(-4px);

    box-shadow: 6px 6px 0px var(--dark);

}


/* =========================================
   SEARCH RESULT
========================================= */

.gallery-card.is-hidden {

    opacity: 0 !important;

    transform: scale(0) !important;

    position: absolute !important;

    pointer-events: none !important;

    transition: all 0.3s ease;

}


.neo-card.gallery-card {

    transition: all 0.3s ease;

}


/* =========================================
   DELETE MODAL
========================================= */

.neo-modal-overlay {

    position: fixed;

    inset: 0;

    display: flex;

    align-items: center;

    justify-content: center;

    background-color: rgba(0, 0, 0, 0.6);

    z-index: 9999;

    padding: 1rem;

}


.neo-modal-box {

    width: 100%;

    max-width: 500px;

    background-color: #ffffff;

    border: 3px solid var(--dark);

    box-shadow: 8px 8px 0 var(--dark);

    transform: rotate(-1deg);

    box-sizing: border-box;

}


.neo-modal-header {

    padding: 1rem 1.25rem;

    background-color: #ff5757;

    color: #ffffff;

    border-bottom: 3px solid var(--dark);

    font-size: 1.2rem;

    font-weight: 900;

}


.neo-modal-body {

    padding: 1.5rem 1.25rem;

    color: var(--dark);

    line-height: 1.5;

}


.neo-modal-body p {

    margin: 0;

}


#deleteGalleryTargetCaption {

    display: block;

    margin-top: 0.5rem;

    padding: 0.5rem 0.75rem;

    background-color: var(--yellow-light);

    border: 2px solid var(--dark);

    font-weight: 800;

    overflow-wrap: anywhere;

}


.neo-modal-footer {

    display: flex;

    justify-content: flex-end;

    gap: 0.75rem;

    padding: 1rem 1.25rem;

    border-top: 3px solid var(--dark);

}


.neo-btn-modal {

    border: 2px solid var(--dark);

    padding: 0.65rem 1rem;

    font-weight: 800;

    cursor: pointer;

    box-shadow: 3px 3px 0 var(--dark);

    transition:
        transform 0.1s ease,
        box-shadow 0.1s ease;

}


.neo-btn-modal:active {

    transform: translate(3px, 3px);

    box-shadow: 0 0 0 var(--dark);

}


.btn-cancel {

    background-color: #ffffff;

    color: var(--dark);

}


.btn-confirm {

    background-color: #ff5757;

    color: #ffffff;

}


/* =========================================
   MOBILE MODAL
========================================= */

@media (max-width: 500px) {

    .neo-modal-box {

        max-width: 100%;

    }

    .neo-modal-footer {

        flex-direction: column;

    }

    .neo-btn-modal {

        width: 100%;

    }

}


/* =========================================
   MOBILE GRID
========================================= */

@media (max-width: 768px) {

    #gallery-list {

        grid-template-columns: 1fr !important;

    }

}

</style>


<script>

/* =========================================
   SEARCH GALLERY
========================================= */

document.addEventListener('DOMContentLoaded', function () {

    const searchInput =
        document.getElementById('gallery-search');

    const galleries =
        document.querySelectorAll('.gallery-card');

    const noResult =
        document.getElementById('no-result');


    if (!searchInput || galleries.length === 0) {

        return;

    }


    searchInput.addEventListener('input', function () {

        const keyword =
            this.value.toLowerCase().trim();

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

    });

});

</script>


<script>

/* =========================================
   DELETE GALLERY MODAL
========================================= */

let activeDeleteGalleryFormId = null;


function openDeleteGalleryModal(
    galleryCaption,
    formId
) {

    activeDeleteGalleryFormId = formId;


    const overlay =
        document.getElementById(
            'deleteGalleryModalOverlay'
        );


    const targetCaption =
        document.getElementById(
            'deleteGalleryTargetCaption'
        );


    const submitButton =
        document.getElementById(
            'deleteGallerySubmitBtn'
        );


    targetCaption.textContent =
        galleryCaption;


    overlay.style.display = 'flex';


    submitButton.onclick = function () {

        if (activeDeleteGalleryFormId) {

            document
                .getElementById(
                    activeDeleteGalleryFormId
                )
                .submit();

        }

    };

}


function closeDeleteGalleryModal() {

    const overlay =
        document.getElementById(
            'deleteGalleryModalOverlay'
        );


    overlay.style.display = 'none';


    activeDeleteGalleryFormId = null;

}


window.addEventListener('click', function (event) {

    const overlay =
        document.getElementById(
            'deleteGalleryModalOverlay'
        );


    if (event.target === overlay) {

        closeDeleteGalleryModal();

    }

});

</script>