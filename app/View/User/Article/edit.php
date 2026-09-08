<!-- Panggil CSS Pell -->
<link rel="stylesheet" href="/assets/pell-js/pell.min.css">

<style>
    /* ========================================================
       PELL EDITOR
    ======================================================== */

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
        transition: all 0.2s ease;
    }

    .pell-button:hover {
        background-color: var(--yellow-light) !important;
    }

    .pell-button.pell-button-selected {
        background-color: var(--yellow-light) !important;
        color: var(--dark) !important;
        border-bottom: 2px solid var(--dark) !important;
    }

    .pell-button[title="Link"],
    .pell-button:last-child {
        position: relative;
        color: transparent !important;
        overflow: hidden;
    }

    .pell-button[title="Link"]::after,
    .pell-button:last-child::after {
        content: "🔗";
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
        color: var(--dark) !important;
        filter: drop-shadow(0px 0px 0px var(--dark)) contrast(200%) brightness(0.1);
    }

    .pell-content {
        padding: 1rem !important;
        min-height: 250px !important;
        outline: none;
        font-family: 'Inter', sans-serif;
        line-height: 1.6;
    }

    .pell-content ul {
        list-style-type: disc !important;
        margin-top: 0.5rem !important;
        margin-bottom: 0.5rem !important;
        padding-left: 2rem !important;
    }

    .pell-content ol {
        list-style-type: decimal !important;
        margin-top: 0.5rem !important;
        margin-bottom: 0.5rem !important;
        padding-left: 2rem !important;
    }

    .pell-content li {
        display: list-item !important;
        margin-bottom: 0.25rem !important;
    }


    /* ========================================================
       USER ITEM
    ======================================================== */

    .selected-user-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 8px;
        background: #fff;
    }

    .selected-user-info {
        min-width: 0;
    }

    .selected-user-name {
        font-weight: bold;
    }

    .selected-user-email {
        font-size: 13px;
        color: #666;
        word-break: break-word;
    }


    /* ========================================================
       EXISTING IMAGE
    ======================================================== */

    .existing-image-card {
        position: relative;
        border: 2px solid var(--dark);
        border-radius: 8px;
        padding: 10px;
        background: #fff;
    }

    .existing-image-card img {
        width: 100%;
        height: 160px;
        object-fit: cover;
        border-radius: 6px;
        display: block;
    }

    .existing-image-delete {
        margin-top: 10px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .existing-image-caption {
        width: 100%;
        margin-top: 10px;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 6px;
        box-sizing: border-box;
    }

    .existing-image-card.marked-delete {
        opacity: 0.5;
        border: 2px dashed #dc2626;
    }


    /* ========================================================
       NEW IMAGE PREVIEW
    ======================================================== */

    #imagePreview {
        display: grid;
        grid-template-columns: repeat(auto-fill,
                minmax(140px, 1fr));
        gap: 15px;
    }

    .new-image-card {
        position: relative;
    }

    .new-image-card img {
        width: 100%;
        height: 120px;
        object-fit: cover;
        border-radius: 8px;
        display: block;
    }
</style>


<!-- ========================================================
     HERO
======================================================== -->

<section class="hero">
    <div class="container">

        <div class="hero-content">

            <!-- ==================================================
                 ERROR
            ================================================== -->

            <?php if (isset($model['error'])) { ?>

                <div class="font-semibold neo-box login-card bg-primary mb-lg">
                    <?= htmlspecialchars($model['error']) ?>
                </div>

            <?php } else { ?>

                <!-- ==================================================
                 CARD
            ================================================== -->

                <div class="neo-card login-card" style="
                    width: 100%;
                    max-width: 700px;
                    margin: 0 auto;
                    box-sizing: border-box;
                ">

                    <!-- HEADER -->

                    <div class="text-center mb-lg">

                        <h3>
                            <?= htmlspecialchars(
                                $model['title'] ?? 'Edit Article'
                            ) ?>
                        </h3>

                    </div>


                    <!-- ==================================================
                     FORM
                ================================================== -->

                    <form action="" method="post" enctype="multipart/form-data" id="articleForm">

                        <!-- ID ARTICLE -->

                        <input type="hidden" name="id" value="<?= (int) ($model['article']['id'] ?? 0) ?>">


                        <!-- ==================================================
                         TITLE
                    ================================================== -->

                        <div class="mb-md">

                            <label for="title" class="font-semibold block mb-sm">
                                Title
                            </label>

                            <input type="text" name="title" id="title" class="login-input"
                                placeholder="Masukkan judul artikel" value="<?= htmlspecialchars(
                                    $_POST['title']
                                    ?? $model['article']['title']
                                    ?? ''
                                ) ?>" required>

                        </div>


                        <!-- ==================================================
                         CONTENT
                    ================================================== -->

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


                        <!-- ==================================================
                         SHARE ARTICLE
                    ================================================== -->

                        <div class="mb-lg">

                            <label for="userSearch" class="font-semibold block mb-sm">
                                Bagikan Artikel Dengan
                            </label>


                            <!-- SEARCH -->

                            <input type="text" id="userSearch" class="login-input"
                                placeholder="Cari berdasarkan nama atau email..." autocomplete="off">


                            <!-- SEARCH RESULT -->

                            <div id="userSearchResult" style="
                                display: none;
                                margin-top: 8px;
                                border: 1px solid #ddd;
                                border-radius: 8px;
                                background: white;
                                max-height: 220px;
                                overflow-y: auto;
                            "></div>


                            <!-- SELECTED USERS -->

                            <div id="selectedUsers" style="
                                display: flex;
                                flex-direction: column;
                                gap: 8px;
                                margin-top: 12px;
                            "></div>

                        </div>

                        <!-- ==================================================
     TAGS
================================================== -->

                        <div class="mb-lg">

                            <label for="tagSearch" class="font-semibold block mb-sm">
                                Tags
                            </label>


                            <!-- SEARCH TAG -->

                            <input type="text" id="tagSearch" class="login-input" placeholder="Cari tag..."
                                autocomplete="off">


                            <!-- SEARCH RESULT -->

                            <div id="tagSearchResult" style="
                            display: none;
                            margin-top: 8px;
                            border: 1px solid #ddd;
                            border-radius: 8px;
                            background: white;
                            max-height: 220px;
                            overflow-y: auto;
                        "></div>


                            <!-- SELECTED TAGS -->

                            <div id="selectedTags" style="
                            display: flex;
                            flex-wrap: wrap;
                            gap: 8px;
                            margin-top: 12px;
                        "></div>

                        </div>


                        <!-- ==================================================
                         EXISTING IMAGES
                    ================================================== -->

                        <?php
                        $images = $model['images'] ?? [];
                        ?>

                        <?php if (!empty($images)) { ?>

                            <div class="mb-lg">

                                <label class="font-semibold block mb-sm">
                                    Gambar Artikel Saat Ini
                                </label>


                                <div style="
    display: grid;
    grid-template-columns:
        repeat(
            auto-fill,
            minmax(180px, 1fr)
        );
    gap: 15px;
">

                                    <?php foreach ($images as $image) { ?>

                                        <?php
                                        $imageId = (int) ($image['id'] ?? 0);
                                        $imageName = $image['image'] ?? '';
                                        $caption = $image['caption'] ?? '';
                                        ?>

                                        <div class="existing-image-card" data-image-card="<?= $imageId ?>" style="
                border: 1px solid #ddd;
                border-radius: 8px;
                padding: 10px;
            ">

                                            <!-- IMAGE -->

                                            <img src="/uploads/articles/<?= htmlspecialchars($imageName) ?>"
                                                alt="<?= htmlspecialchars($caption) ?>" style="
                    width: 100%;
                    height: 180px;
                    object-fit: cover;
                    border-radius: 6px;
                    display: block;
                    margin-bottom: 10px;
                ">


                                            <!-- CAPTION -->

                                            <input type="text" name="existingCaptions[<?= $imageId ?>]"
                                                class="login-input existing-image-caption" placeholder="Caption gambar..."
                                                value="<?= htmlspecialchars($caption) ?>">


                                            <!-- DELETE -->

                                            <label class="existing-image-delete" style="
                                            display: flex;
                                            align-items: center;
                                            gap: 6px;
                                            margin-top: 8px;
                                            cursor: pointer;
                                        ">

                                                <input type="checkbox" name="deleteImages[]" value="<?= $imageId ?>"
                                                    class="delete-image-checkbox">

                                                <span>Hapus gambar</span>

                                            </label>

                                        </div>

                                    <?php } ?>

                                </div>

                            </div>

                        <?php } ?>


                        <!-- ==================================================
                         ADD NEW IMAGES
                    ================================================== -->

                        <div class="mb-lg">

                            <label for="images" class="font-semibold block mb-sm">
                                Tambah Article Images
                            </label>


                            <input type="file" name="images[]" id="images" accept="image/jpeg,image/png,image/webp" multiple
                                class="login-input">


                            <p class="mt-sm">
                                Kamu bisa menambahkan lebih dari satu gambar baru.
                            </p>

                        </div>


                        <!-- ==================================================
                         NEW IMAGE PREVIEW
                    ================================================== -->

                        <div id="imagePreview" class="mb-lg"></div>


                        <!-- ==================================================
                         SUBMIT
                    ================================================== -->

                        <button type="submit" class="neo-btn w-full">
                            Simpan Perubahan
                        </button>

                    </form>

                </div>

            <?php } ?>

        </div>

    </div>
</section>


<!-- ========================================================
     PELL
======================================================== -->

<script src="/assets/pell-js/pell.min.js"></script>

<script>

    /*
    |--------------------------------------------------------------------------
    | PELL EDITOR
    |--------------------------------------------------------------------------
    */

    const hiddenInput =
        document.getElementById('hiddenContent');

    const editorElement =
        document.getElementById('editor');


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
    | LOAD CONTENT LAMA
    |--------------------------------------------------------------------------
    */

    if (hiddenInput.value.trim() !== '') {

        editor.content.innerHTML =
            hiddenInput.value;

    }


    /*
    |--------------------------------------------------------------------------
    | MULTI USER
    |--------------------------------------------------------------------------
    */

    const userSearch =
        document.getElementById('userSearch');

    const userSearchResult =
        document.getElementById('userSearchResult');

    const selectedUsersContainer =
        document.getElementById('selectedUsers');


    /*
    |--------------------------------------------------------------------------
    | USER YANG SUDAH TERHUBUNG
    |--------------------------------------------------------------------------
    |
    | articleUsers dikirim dari controller:
    |
    | $articleUsers
    |
    */

    const existingUsers = <?= json_encode(
        array_map(
            function ($user) {

        return [
            'id' => (int) $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'img' => $user->img ?? null
        ];

    },
            $model['articleUsers'] ?? []
        ),
        JSON_UNESCAPED_UNICODE
    ) ?>;


    /*
    |--------------------------------------------------------------------------
    | CURRENT USER
    |--------------------------------------------------------------------------
    */

    const currentUserId =
        <?= (int) ($model['currentUserId'] ?? 0) ?>;


    /*
    |--------------------------------------------------------------------------
    | SELECTED USERS
    |--------------------------------------------------------------------------
    */

    const selectedUsers = new Map();


    /*
    |--------------------------------------------------------------------------
    | LOAD USER YANG SUDAH TERHUBUNG
    |--------------------------------------------------------------------------
    |
    | User yang sedang login tidak perlu ditampilkan
    | sebagai selected user karena controller akan selalu
    | memasukkannya kembali ketika sync.
    |
    */

    existingUsers.forEach(user => {

        const userId = Number(user.id);

        if (userId === currentUserId) {
            return;
        }

        selectedUsers.set(
            userId,
            user
        );

    });


    renderSelectedUsers();


    /*
    |--------------------------------------------------------------------------
    | SEARCH USER
    |--------------------------------------------------------------------------
    */

    userSearch.addEventListener(
        'input',
        async function () {

            const keyword =
                this.value.trim();


            if (keyword === '') {

                userSearchResult.innerHTML = '';

                userSearchResult.style.display =
                    'none';

                return;

            }


            try {

                const response = await fetch(
                    `/user/search?keyword=${encodeURIComponent(keyword)
                    }`
                );


                if (!response.ok) {

                    throw new Error(
                        'Gagal mencari user.'
                    );

                }


                const users =
                    await response.json();


                renderUserSearch(users);

            } catch (error) {

                console.error(error);


                userSearchResult.innerHTML = `
                    <div style="padding: 12px;">
                        Gagal mencari user.
                    </div>
                `;


                userSearchResult.style.display =
                    'block';

            }

        }
    );


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

            userSearchResult.style.display =
                'block';

            return;

        }


        users.forEach(user => {

            const userId =
                Number(user.id);


            /*
            | Jangan tampilkan user yang sudah dipilih
            */

            if (selectedUsers.has(userId)) {

                return;

            }


            /*
            | Jangan tampilkan current user
            */

            if (userId === currentUserId) {

                return;

            }


            const item =
                document.createElement('div');


            item.style.padding =
                '10px 12px';

            item.style.cursor =
                'pointer';

            item.style.borderBottom =
                '1px solid #eee';


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


            item.addEventListener(
                'click',
                function () {

                    addSelectedUser(user);

                }
            );


            userSearchResult.appendChild(item);

        });


        userSearchResult.style.display =
            'block';

    }


    /*
    |--------------------------------------------------------------------------
    | ADD SELECTED USER
    |--------------------------------------------------------------------------
    */

    function addSelectedUser(user) {

        const userId =
            Number(user.id);


        if (userId === currentUserId) {

            return;

        }


        if (selectedUsers.has(userId)) {

            return;

        }


        selectedUsers.set(
            userId,
            user
        );


        renderSelectedUsers();


        userSearch.value = '';

        userSearchResult.innerHTML = '';

        userSearchResult.style.display =
            'none';

    }


    /*
    |--------------------------------------------------------------------------
    | RENDER SELECTED USERS
    |--------------------------------------------------------------------------
    */

    function renderSelectedUsers() {

        selectedUsersContainer.innerHTML = '';


        if (selectedUsers.size === 0) {

            selectedUsersContainer.innerHTML = `
                <div style="
                    padding: 10px;
                    color: #666;
                    border: 1px dashed #ccc;
                    border-radius: 8px;
                ">
                    Belum ada user lain yang terhubung.
                </div>
            `;

            return;

        }


        selectedUsers.forEach(
            (user, userId) => {

                const wrapper =
                    document.createElement('div');


                wrapper.className =
                    'selected-user-item';


                wrapper.innerHTML = `

                    <div class="selected-user-info">

                        <div class="selected-user-name">
                            ${escapeHtml(user.name)}
                        </div>

                        <div class="selected-user-email">
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


                const removeButton =
                    wrapper.querySelector('button');


                removeButton.addEventListener(
                    'click',
                    function () {

                        selectedUsers.delete(
                            userId
                        );

                        renderSelectedUsers();

                    }
                );


                selectedUsersContainer.appendChild(
                    wrapper
                );

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | ESCAPE HTML
    |--------------------------------------------------------------------------
    */

    function escapeHtml(value) {

        const div =
            document.createElement('div');


        div.textContent =
            value ?? '';


        return div.innerHTML;

    }

    /*
|--------------------------------------------------------------------------
| MULTI TAG
|--------------------------------------------------------------------------
*/

    const tagSearch =
        document.getElementById('tagSearch');

    const tagSearchResult =
        document.getElementById('tagSearchResult');

    const selectedTagsContainer =
        document.getElementById('selectedTags');


    /*
    |--------------------------------------------------------------------------
    | TAG YANG SUDAH TERHUBUNG
    |--------------------------------------------------------------------------
    */

    const existingTags = <?= json_encode(
        array_map(
            function ($tag) {
                return [
                    'id' => (int) $tag['tag_id'],
                    'name' => $tag['name']
                ];
            },
            $model['articleTags'] ?? []
        ),
        JSON_UNESCAPED_UNICODE
    ) ?>;


    /*
    |--------------------------------------------------------------------------
    | SELECTED TAGS
    |--------------------------------------------------------------------------
    */

    const selectedTags = new Map();


    /*
    |--------------------------------------------------------------------------
    | LOAD TAG LAMA
    |--------------------------------------------------------------------------
    */

    existingTags.forEach(tag => {

        const tagId =
            Number(tag.id);

        selectedTags.set(
            tagId,
            tag
        );

    });


    renderSelectedTags();


    /*
    |--------------------------------------------------------------------------
    | SEARCH TAG
    |--------------------------------------------------------------------------
    */

    tagSearch.addEventListener(
        'input',
        async function () {

            const keyword =
                this.value.trim();


            if (keyword === '') {

                tagSearchResult.innerHTML = '';

                tagSearchResult.style.display =
                    'none';

                return;

            }


            try {

                const response = await fetch(
                    `/tag/search?keyword=${encodeURIComponent(keyword)}`
                );


                if (!response.ok) {

                    throw new Error(
                        'Gagal mencari tag.'
                    );

                }


                const tags =
                    await response.json();


                renderTagSearch(tags);

            } catch (error) {

                console.error(error);


                tagSearchResult.innerHTML = `
                <div style="padding: 12px;">
                    Gagal mencari tag.
                </div>
            `;


                tagSearchResult.style.display =
                    'block';

            }

        }
    );


    /*
    |--------------------------------------------------------------------------
    | RENDER SEARCH RESULT
    |--------------------------------------------------------------------------
    */

    function renderTagSearch(tags) {

        tagSearchResult.innerHTML = '';


        if (!tags.length) {

            tagSearchResult.innerHTML = `
            <div style="padding: 12px;">
                Tag tidak ditemukan.
            </div>
        `;

            tagSearchResult.style.display =
                'block';

            return;

        }


        tags.forEach(tag => {

            const tagId =
                Number(tag.id);


            /*
            | Jangan tampilkan tag
            | yang sudah dipilih
            */

            if (selectedTags.has(tagId)) {

                return;

            }


            const item =
                document.createElement('div');


            item.style.padding =
                '10px 12px';

            item.style.cursor =
                'pointer';

            item.style.borderBottom =
                '1px solid #eee';


            item.innerHTML = `

            <strong>
                ${escapeHtml(tag.name)}
            </strong>

        `;


            item.addEventListener(
                'click',
                function () {

                    addSelectedTag(tag);

                }
            );


            tagSearchResult.appendChild(item);

        });


        tagSearchResult.style.display =
            'block';

    }


    /*
    |--------------------------------------------------------------------------
    | ADD SELECTED TAG
    |--------------------------------------------------------------------------
    */

    function addSelectedTag(tag) {

        const tagId =
            Number(tag.id);


        if (selectedTags.has(tagId)) {

            return;

        }


        selectedTags.set(
            tagId,
            tag
        );


        renderSelectedTags();


        tagSearch.value = '';

        tagSearchResult.innerHTML = '';

        tagSearchResult.style.display =
            'none';

    }


    /*
    |--------------------------------------------------------------------------
    | RENDER SELECTED TAGS
    |--------------------------------------------------------------------------
    */

    function renderSelectedTags() {

        selectedTagsContainer.innerHTML = '';


        if (selectedTags.size === 0) {

            selectedTagsContainer.innerHTML = `
            <div style="
                padding: 10px;
                color: #666;
                border: 1px dashed #ccc;
                border-radius: 8px;
            ">
                Belum ada tag.
            </div>
        `;

            return;

        }


        selectedTags.forEach(
            (tag, tagId) => {

                const wrapper =
                    document.createElement('div');


                wrapper.style.display =
                    'flex';

                wrapper.style.alignItems =
                    'center';

                wrapper.style.gap =
                    '8px';

                wrapper.style.padding =
                    '6px 10px';

                wrapper.style.border =
                    '2px solid var(--dark)';

                wrapper.style.borderRadius =
                    '4px';

                wrapper.style.backgroundColor =
                    'var(--yellow-light)';


                wrapper.innerHTML = `

                <span>
                    ${escapeHtml(tag.name)}
                </span>


                <button
                    type="button"
                    class="neo-btn"
                    style="
                        padding: 2px 7px;
                        width: auto;
                        font-size: 12px;
                    "
                >
                    ×
                </button>


                <input
                    type="hidden"
                    name="selectedTags[]"
                    value="${tagId}"
                >

            `;


                const removeButton =
                    wrapper.querySelector('button');


                removeButton.addEventListener(
                    'click',
                    function () {

                        selectedTags.delete(
                            tagId
                        );

                        renderSelectedTags();

                    }
                );


                selectedTagsContainer.appendChild(
                    wrapper
                );

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | DELETE EXISTING IMAGE
    |--------------------------------------------------------------------------
    */

    document
        .querySelectorAll('.delete-image-checkbox')
        .forEach(checkbox => {

            checkbox.addEventListener(
                'change',
                function () {

                    const card =
                        this.closest(
                            '.existing-image-card'
                        );


                    if (this.checked) {

                        card.classList.add(
                            'marked-delete'
                        );

                    } else {

                        card.classList.remove(
                            'marked-delete'
                        );

                    }

                }
            );

        });


    /*
    |--------------------------------------------------------------------------
    | NEW IMAGE PREVIEW
    |--------------------------------------------------------------------------
    */

    const imageInput =
        document.getElementById('images');

    const imagePreview =
        document.getElementById('imagePreview');


    const dataTransfer =
        new DataTransfer();


    imageInput.addEventListener(
        'change',
        function () {

            /*
            | Tambahkan file baru
            */

            for (const file of this.files) {

                const alreadyExists =
                    Array.from(
                        dataTransfer.files
                    ).some(
                        existingFile =>

                            existingFile.name ===
                            file.name &&

                            existingFile.size ===
                            file.size &&

                            existingFile.lastModified ===
                            file.lastModified
                    );


                if (!alreadyExists) {

                    dataTransfer.items.add(
                        file
                    );

                }

            }


            /*
            | Masukkan kembali semua file
            */

            imageInput.files =
                dataTransfer.files;


            renderPreview();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | RENDER NEW IMAGE PREVIEW
    |--------------------------------------------------------------------------
    */

    function renderPreview() {

        imagePreview.innerHTML = '';


        Array.from(
            dataTransfer.files
        ).forEach(
            (file, index) => {

                if (!file.type.startsWith('image/')) {

                    return;

                }


                const reader =
                    new FileReader();


                reader.onload =
                    function (event) {

                        const wrapper =
                            document.createElement('div');


                        wrapper.className =
                            'new-image-card';


                        wrapper.innerHTML = `

                            <img
                                src="${event.target.result}"
                                alt="${escapeHtml(file.name)}"
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
                                    box-sizing: border-box;
                                "
                            >

                        `;


                        imagePreview.appendChild(
                            wrapper
                        );

                    };


                reader.readAsDataURL(file);

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | FORM VALIDATION
    |--------------------------------------------------------------------------
    */

    document
        .getElementById('articleForm')
        .addEventListener(
            'submit',
            function (e) {

                const contentValue =
                    hiddenInput.value.trim();


                if (
                    contentValue === ''
                    ||
                    contentValue === '<p><br></p>'
                ) {

                    e.preventDefault();


                    alert(
                        'Konten artikel tidak boleh kosong!'
                    );


                    return;

                }

            }
        );

</script>