<!-- Panggil CSS Pell di bagian atas halaman (Sesuaikan jalurnya) -->
<link rel="stylesheet" href="/assets/pell-js/pell.min.css">

<style>
    /* KUSTOMISASI PELL AGAR BERGAYA NEO-BRUTALISM */
    .pell {
        border: 2px solid var(--dark) !important;
        box-shadow: 4px 4px 0px var(--dark) !important;
        border-radius: 4px !important;
        background-color: #ffffff;
        overflow: hidden;
    }

    .pell-actionbar {
        background-color: #ffffff !important;
        border-bottom: 2px solid var(--dark) !important;
        display: flex;
        flex-wrap: wrap;
    }

    .pell-button {
        background-color: transparent !important;
        border: none !important;
        color: var(--dark) !important;
        font-weight: bold !important;
        cursor: pointer;
        padding: 8px 12px !important;
        height: auto !important;
        width: auto !important;
    }

    .pell-button:hover {
        background-color: var(--yellow-light) !important;
    }

    .pell-content {
        padding: 1rem !important;
        min-height: 250px !important;
        outline: none;
        font-family: 'Inter', sans-serif;
        line-height: 1.6;
    }

    /* Pastikan Unordered List memunculkan simbol Bullet Bulat */
    .pell-content ul {
        list-style-type: disc !important;
        margin-top: 0.5rem !important;
        margin-bottom: 0.5rem !important;
        padding-left: 2rem !important;
        /* Memberikan jarak inden teks ke kanan agar rapi */
    }

    /* Pastikan Ordered List memunculkan simbol Angka Berurutan */
    .pell-content ol {
        list-style-type: decimal !important;
        margin-top: 0.5rem !important;
        margin-bottom: 0.5rem !important;
        padding-left: 2rem !important;
        /* Memberikan jarak inden teks ke kanan agar rapi */
    }

    /* Desain item list di dalam editor agar memiliki spasi yang nyaman dibaca */
    .pell-content li {
        display: list-item !important;
        /* Mengembalikan perilaku elemen sebagai list-item native browser */
        margin-bottom: 0.25rem !important;
    }

    /* ... CSS Kustomisasi Pell Anda yang sudah ada ... */

    .pell-button {
        background-color: transparent !important;
        border: none !important;
        color: var(--dark) !important;
        font-weight: bold !important;
        cursor: pointer;
        padding: 8px 12px !important;
        height: auto !important;
        width: auto !important;
        transition: all 0.2s ease;
    }

    .pell-button:hover {
        background-color: var(--yellow-light) !important;
    }

    /* ========================================================
   KUNCI PERBAIKAN: MENJAGA TOMBOL TETAP MENYALA SAAT AKTIF
   ======================================================== */
    .pell-button.pell-button-selected {
        background-color: var(--yellow-light) !important;
        /* Warna stabilo hijau tetap bertahan */
        color: var(--dark) !important;
        border-bottom: 2px solid var(--dark) !important;
        /* Opsional: memberi garis bawah penegas */
    }

    /* Menargetkan tombol link Pell secara spesifik */
    .pell-button[title="Link"],
    .pell-button:last-child {
        position: relative;
        color: transparent !important;
        /* Menyembunyikan emoji asli berwarna abu-abu */
        overflow: hidden;
    }

    /* Membuat tiruan ikon rantai baru dengan warna murni dari var(--dark) */
    .pell-button[title="Link"]::after,
    .pell-button:last-child::after {
        content: "🔗";
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
        color: var(--dark) !important;

        /* KUNCI MEWARNAI EMOJI: Membuat bayangan sewarna var(--dark) lalu digeser menutupi objek asli */
        filter: drop-shadow(0px 0px 0px var(--dark)) contrast(200%) brightness(0.1);
    }
</style>

<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <div class="hero-content">

            <!-- Notifikasi Error -->
            <?php if (isset($model['error'])) { ?>
                <div class="font-semibold neo-box login-card bg-primary mb-lg">
                    <?= $model['error'] ?>
                </div>
            <?php } ?>

            <!-- Card Form Artikel -->
            <div class="neo-card login-card"
                style="width: 100%; max-width: 700px; margin: 0 auto; box-sizing: border-box;">

                <div class="text-center mb-lg">
                    <h3><?= $model['title'] ?? 'Tambah Artikel' ?></h3>
                    <p class="mt-sm">
                        Tulis berita atau informasi terbaru organisasi.
                    </p>
                </div>

                <form action="" method="post" enctype="multipart/form-data" id="articleForm">

                    <!-- ============================== -->
                    <!-- JUDUL -->
                    <!-- ============================== -->

                    <div class="mb-md">
                        <label for="title" class="font-semibold block mb-sm">
                            Title
                        </label>

                        <input type="text" name="title" id="title" class="login-input"
                            placeholder="Masukkan judul artikel" value="<?= htmlspecialchars($_POST['title'] ?? '') ?>"
                            required>
                    </div>


                    <!-- ============================== -->
                    <!-- CONTENT -->
                    <!-- ============================== -->

                    <div class="mb-lg">

                        <label class="font-semibold block mb-sm">
                            Content
                        </label>

                        <div id="editor" class="pell"></div>

                    </div>


                    <!-- Hidden Content -->
                    <input type="hidden" name="content" id="hiddenContent" value="<?= htmlspecialchars(
                        $_POST['content']
                        ?? $model['article']['content']
                        ?? ''
                    ) ?>">


                    <!-- ============================== -->
                    <!-- SHARE ARTICLE -->
                    <!-- ============================== -->

                    <div class="mb-lg">

                        <label for="userSearch" class="font-semibold block mb-sm">
                            Bagikan Artikel Dengan
                        </label>

                        <input type="text" id="userSearch" class="login-input"
                            placeholder="Cari berdasarkan nama atau email..." autocomplete="off">

                        <!-- Hasil Pencarian -->
                        <div id="userSearchResult" style="
                                display: none;
                                margin-top: 8px;
                                border: 1px solid #ddd;
                                border-radius: 8px;
                                background: white;
                                max-height: 220px;
                                overflow-y: auto;
                            "></div>


                        <!-- User yang Dipilih -->
                        <div id="selectedUsers" style="
                                display: flex;
                                flex-direction: column;
                                gap: 8px;
                                margin-top: 12px;
                            "></div>

                    </div>


                    <!-- ============================== -->
                    <!-- IMAGE -->
                    <!-- ============================== -->

                    <div class="mb-lg">

                        <label for="images" class="font-semibold block mb-sm">
                            Article Images
                        </label>

                        <input type="file" name="images[]" id="images" accept="image/jpeg,image/png,image/webp" multiple
                            class="login-input">

                        <p class="mt-sm">
                            Kamu bisa memilih lebih dari satu gambar.
                        </p>

                    </div>


                    <!-- ============================== -->
                    <!-- IMAGE PREVIEW -->
                    <!-- ============================== -->

                    <div id="imagePreview" class="mb-lg" style="
                            display: grid;
                            grid-template-columns: repeat(
                                auto-fill,
                                minmax(140px, 1fr)
                            );
                            gap: 15px;
                        ">
                    </div>


                    <!-- ============================== -->
                    <!-- SUBMIT -->
                    <!-- ============================== -->

                    <button type="submit" class="neo-btn w-full">
                        Simpan Artikel
                    </button>

                </form>

            </div>
        </div>
    </div>
</section>


<!-- Pell -->
<script src="/assets/pell-js/pell.min.js"></script>

<script>

    /*
    |--------------------------------------------------------------------------
    | PELL EDITOR
    |--------------------------------------------------------------------------
    */

    const hiddenInput = document.getElementById('hiddenContent');
    const editorElement = document.getElementById('editor');

    const editor = pell.init({

        element: editorElement,

        onChange: html => {

            hiddenInput.value = html;

        },

        defaultParagraphSeparator: 'p',

        actions: [
            'bold',
            'italic',
            'underline',
            'strikethrough',
            'heading1',
            'heading2',
            'paragraph',
            'olist',
            'ulist',
            'link'
        ]

    });


    /*
    |--------------------------------------------------------------------------
    | LOAD CONTENT
    |--------------------------------------------------------------------------
    */

    if (hiddenInput.value.trim() !== '') {

        editor.content.innerHTML = hiddenInput.value;

    }


    /*
    |--------------------------------------------------------------------------
    | MULTI USER
    |--------------------------------------------------------------------------
    */

    const userSearch = document.getElementById('userSearch');
    const userSearchResult = document.getElementById('userSearchResult');
    const selectedUsersContainer =
        document.getElementById('selectedUsers');


    // Menyimpan user yang sudah dipilih
    const selectedUsers = new Map();


    /*
    |--------------------------------------------------------------------------
    | SEARCH USER
    |--------------------------------------------------------------------------
    */

    userSearch.addEventListener('input', async function () {

        const keyword = this.value.trim();

        // Jika kosong, sembunyikan hasil pencarian
        if (keyword === '') {

            userSearchResult.innerHTML = '';
            userSearchResult.style.display = 'none';

            return;
        }


        try {

            const response = await fetch(
                `/user/search?keyword=${encodeURIComponent(keyword)}`
            );

            if (!response.ok) {
                throw new Error('Gagal mencari user.');
            }

            const users = await response.json();

            renderUserSearch(users);

        } catch (error) {

            console.error(error);

            userSearchResult.innerHTML = `
                <div style="padding: 12px;">
                    Gagal mencari user.
                </div>
            `;

            userSearchResult.style.display = 'block';

        }

    });


    /*
    |--------------------------------------------------------------------------
    | RENDER SEARCH RESULT
    |--------------------------------------------------------------------------
    */

    function renderUserSearch(users) {

        userSearchResult.innerHTML = '';

        if (!users.length) {

            userSearchResult.innerHTML = `
                <div style="padding: 12px;">
                    User tidak ditemukan.
                </div>
            `;

            userSearchResult.style.display = 'block';

            return;
        }


        users.forEach(user => {

            // Jangan tampilkan user yang sudah dipilih
            if (selectedUsers.has(Number(user.id))) {
                return;
            }


            const item = document.createElement('div');

            item.style.padding = '10px 12px';
            item.style.cursor = 'pointer';
            item.style.borderBottom = '1px solid #eee';


            item.innerHTML = `
                <strong>
                    ${escapeHtml(user.name)}
                </strong>

                <div style="
                    font-size: 13px;
                    color: #666;
                ">
                    ${escapeHtml(user.email)}
                </div>
            `;


            item.addEventListener('click', function () {

                addSelectedUser(user);

            });


            userSearchResult.appendChild(item);

        });


        userSearchResult.style.display = 'block';

    }


    /*
    |--------------------------------------------------------------------------
    | ADD SELECTED USER
    |--------------------------------------------------------------------------
    */

    function addSelectedUser(user) {

        const userId = Number(user.id);

        if (selectedUsers.has(userId)) {
            return;
        }

        selectedUsers.set(userId, user);

        renderSelectedUsers();

        userSearch.value = '';
        userSearchResult.innerHTML = '';
        userSearchResult.style.display = 'none';

    }


    /*
    |--------------------------------------------------------------------------
    | RENDER SELECTED USERS
    |--------------------------------------------------------------------------
    */

    function renderSelectedUsers() {

        selectedUsersContainer.innerHTML = '';


        selectedUsers.forEach((user, userId) => {

            const wrapper = document.createElement('div');

            wrapper.style.display = 'flex';
            wrapper.style.alignItems = 'center';
            wrapper.style.justifyContent = 'space-between';
            wrapper.style.padding = '10px';
            wrapper.style.border = '1px solid #ddd';
            wrapper.style.borderRadius = '8px';


            wrapper.innerHTML = `
                <div>
                    <strong>
                        ${escapeHtml(user.name)}
                    </strong>

                    <div style="
                        font-size: 13px;
                        color: #666;
                    ">
                        ${escapeHtml(user.email)}
                    </div>
                </div>

                <button
                    type="button"
                    class="neo-btn"
                    style="
                        padding: 5px 10px;
                        width: auto;
                    "
                >
                    Hapus
                </button>

                <input
                    type="hidden"
                    name="selectedUsers[]"
                    value="${userId}"
                >
            `;


            const removeButton = wrapper.querySelector('button');

            removeButton.addEventListener('click', function () {

                selectedUsers.delete(userId);

                renderSelectedUsers();

            });


            selectedUsersContainer.appendChild(wrapper);

        });

    }


    /*
    |--------------------------------------------------------------------------
    | ESCAPE HTML
    |--------------------------------------------------------------------------
    */

    function escapeHtml(value) {

        const div = document.createElement('div');

        div.textContent = value ?? '';

        return div.innerHTML;

    }


    /*
    |--------------------------------------------------------------------------
    | IMAGE PREVIEW
    |--------------------------------------------------------------------------
    */

    const imageInput = document.getElementById('images');
    const imagePreview = document.getElementById('imagePreview');

    const dataTransfer = new DataTransfer();


    imageInput.addEventListener('change', function () {

        // Tambahkan file baru ke daftar file
        for (const file of this.files) {

            // Cegah file yang sama ditambahkan dua kali
            const alreadyExists = Array.from(
                dataTransfer.files
            ).some(existingFile =>
                existingFile.name === file.name &&
                existingFile.size === file.size &&
                existingFile.lastModified === file.lastModified
            );


            if (!alreadyExists) {

                dataTransfer.items.add(file);

            }

        }


        // Masukkan kembali semua file ke input
        imageInput.files = dataTransfer.files;

        renderPreview();

    });


    function renderPreview() {

        imagePreview.innerHTML = '';


        Array.from(dataTransfer.files).forEach((file, index) => {

            if (!file.type.startsWith('image/')) {
                return;
            }


            const reader = new FileReader();


            reader.onload = function (event) {

                const wrapper = document.createElement('div');

                wrapper.style.position = 'relative';
                wrapper.style.marginBottom = '15px';


                wrapper.innerHTML = `
                    <img
                        src="${event.target.result}"
                        style="
                            width: 100%;
                            height: 120px;
                            object-fit: cover;
                            border-radius: 8px;
                        "
                    >

                    <p style="
                        margin-top: 5px;
                        font-size: 13px;
                        word-break: break-word;
                    ">
                        ${escapeHtml(file.name)}
                    </p>

                    <input
                        type="text"
                        name="captions[]"
                        placeholder="Caption gambar..."
                        style="
                            width: 100%;
                            padding: 8px;
                            border: 1px solid #ddd;
                            border-radius: 6px;
                        "
                    >
                `;


                imagePreview.appendChild(wrapper);

            };


            reader.readAsDataURL(file);

        });

    }


    /*
    |--------------------------------------------------------------------------
    | FORM VALIDATION
    |--------------------------------------------------------------------------
    */

    document
        .getElementById('articleForm')
        .addEventListener('submit', function (e) {

            const contentValue = hiddenInput.value.trim();


            if (
                contentValue === ''
                ||
                contentValue === '<p><br></p>'
            ) {

                e.preventDefault();

                alert('Konten artikel tidak boleh kosong!');

                return;

            }

        });

</script>