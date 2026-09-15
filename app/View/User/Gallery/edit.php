<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <div class="hero-content">

            <!-- ============================== -->
            <!-- ERROR / FLASH MESSAGE -->
            <!-- ============================== -->

            <?php if (!empty($_SESSION['flash_message'])): ?>

                <div class="font-semibold neo-box login-card bg-primary mb-lg">
                    <?= htmlspecialchars($_SESSION['flash_message']) ?>
                </div>

                <?php unset($_SESSION['flash_message']); ?>

            <?php endif; ?>


            <!-- ============================== -->
            <!-- CARD FORM -->
            <!-- ============================== -->

            <div class="neo-card login-card" style="
                    width: 100%;
                    max-width: 700px;
                    margin: 0 auto;
                    box-sizing: border-box;
                ">


                <!-- ============================== -->
                <!-- HEADER -->
                <!-- ============================== -->

                <div class="text-center mb-lg">

                    <h3>
                        <?= htmlspecialchars(
                            $model['title']
                            ?? 'Edit Gallery'
                        ) ?>
                    </h3>

                    <p class="mt-sm">
                        Edit caption dan koleksi foto gallery.
                    </p>

                </div>


                <!-- ============================== -->
                <!-- FORM -->
                <!-- ============================== -->

                <form action="/gallery/edit/<?= urlencode($model['gallery']->slug) ?>" method="post"
                    enctype="multipart/form-data" id="galleryEditForm">


                    <!-- ============================== -->
                    <!-- HIDDEN ID -->
                    <!-- ============================== -->

                    <input type="hidden" name="id" value="<?= htmlspecialchars(
                        (string) $model['gallery']->id
                    ) ?>">


                    <!-- ============================== -->
                    <!-- CAPTION GALLERY -->
                    <!-- ============================== -->

                    <div class="mb-lg">

                        <label for="caption" class="font-semibold block mb-sm">
                            Caption Gallery
                        </label>


                        <textarea name="caption" id="caption" class="login-input" rows="4"
                            placeholder="Tuliskan caption gallery..." maxlength="500" style="
                                width: 100%;
                                box-sizing: border-box;
                                resize: vertical;
                            "><?= htmlspecialchars(
                                $model['gallery']->caption ?? ''
                            ) ?></textarea>


                        <p class="mt-sm">
                            Maksimal 500 karakter.
                        </p>

                    </div>


                    <!-- ============================== -->
                    <!-- EXISTING IMAGES -->
                    <!-- ============================== -->

                    <div class="mb-lg">

                        <label class="font-semibold block mb-sm">
                            Gallery Images
                        </label>


                        <?php if (!empty($model['images'])): ?>

                            <div id="existingImagePreview" style="
                                    display: grid;
                                    grid-template-columns:
                                        repeat(
                                            auto-fill,
                                            minmax(140px, 1fr)
                                        );
                                    gap: 15px;
                                ">

                                <?php foreach ($model['images'] as $image): ?>

                                    <div class="neo-box" style="
                                            position: relative;
                                            padding: 8px;
                                            background-color: #ffffff;
                                            box-sizing: border-box;
                                        ">

                                        <!-- ============================== -->
                                        <!-- IMAGE -->
                                        <!-- ============================== -->

                                        <div style="
                                            position: relative;
                                        ">

                                            <img src="/uploads/galleries/<?= htmlspecialchars(
                                                $image->image
                                            ) ?>" alt="<?= htmlspecialchars(
                                                 $image->caption ?? 'Gallery image'
                                             ) ?>" style="
                                                    width: 100%;
                                                    height: 120px;
                                                    object-fit: cover;
                                                    display: block;
                                                    border-radius: 4px;
                                                ">

                                        </div>


                                        <!-- ============================== -->
                                        <!-- IMAGE CAPTION -->
                                        <!-- ============================== -->

                                        <div class="mt-sm">

                                            <label class="font-semibold block mb-sm">
                                                Caption gambar
                                            </label>

                                            <input type="text" name="image_captions[<?= (int) $image->id ?>]" value="<?= htmlspecialchars(
                                                   $image->caption ?? ''
                                               ) ?>" maxlength="500" class="login-input"
                                                placeholder="Caption gambar..." style="
                                                    width: 100%;
                                                    box-sizing: border-box;
                                                ">

                                        </div>


                                        <!-- ============================== -->
                                        <!-- DELETE IMAGE -->
                                        <!-- ============================== -->

                                        <label class="font-semibold block mt-sm" style="
                                                cursor: pointer;
                                            ">

                                            <input type="checkbox" name="delete_images[]" value="<?= (int) $image->id ?>">

                                            Hapus gambar

                                        </label>

                                    </div>

                                <?php endforeach; ?>

                            </div>

                        <?php else: ?>

                            <div class="neo-box mb-lg">

                                Belum ada gambar pada gallery ini.

                            </div>

                        <?php endif; ?>

                    </div>


                    <!-- ============================== -->
                    <!-- ADD NEW IMAGES -->
                    <!-- ============================== -->

                    <div class="mb-lg">

                        <label for="images" class="font-semibold block mb-sm">
                            Tambah Gallery Images
                        </label>


                        <input type="file" name="images[]" id="images" accept="
                                image/jpeg,
                                image/png,
                                image/webp,
                                image/svg+xml
                            " multiple class="login-input">


                        <p class="mt-sm">
                            Kamu bisa memilih lebih dari satu gambar.
                            Maksimal ukuran setiap gambar 5 MB.
                        </p>

                    </div>


                    <!-- ============================== -->
                    <!-- NEW IMAGE PREVIEW -->
                    <!-- ============================== -->

                    <div id="imagePreview" class="mb-lg" style="
                            display: grid;
                            grid-template-columns:
                                repeat(
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
                        Update Gallery
                    </button>

                </form>

            </div>

        </div>
    </div>
</section>


<script>

    /*
    |--------------------------------------------------------------------------
    | ELEMENT
    |--------------------------------------------------------------------------
    */

    const imageInput =
        document.getElementById('images');

    const imagePreview =
        document.getElementById('imagePreview');

    const galleryEditForm =
        document.getElementById('galleryEditForm');

    const captionInput =
        document.getElementById('caption');


    /*
    |--------------------------------------------------------------------------
    | DATA TRANSFER
    |--------------------------------------------------------------------------
    |
    | Digunakan supaya user bisa:
    |
    | 1. memilih gambar
    | 2. memilih gambar lagi
    | 3. menghapus gambar tertentu
    |
    */

    const dataTransfer =
        new DataTransfer();


    /*
    |--------------------------------------------------------------------------
    | IMAGE INPUT CHANGE
    |--------------------------------------------------------------------------
    */

    imageInput.addEventListener(
        'change',
        function () {

            /*
            |--------------------------------------------------------------------------
            | Tambahkan file baru
            |--------------------------------------------------------------------------
            */

            for (const file of this.files) {

                /*
                |--------------------------------------------------------------------------
                | Hanya gambar
                |--------------------------------------------------------------------------
                */

                if (!file.type.startsWith('image/')) {
                    continue;
                }


                /*
                |--------------------------------------------------------------------------
                | Cek duplicate
                |--------------------------------------------------------------------------
                */

                const alreadyExists =
                    Array
                        .from(dataTransfer.files)
                        .some(existingFile =>

                            existingFile.name === file.name &&
                            existingFile.size === file.size &&
                            existingFile.lastModified ===
                            file.lastModified

                        );


                /*
                |--------------------------------------------------------------------------
                | Tambahkan file
                |--------------------------------------------------------------------------
                */

                if (!alreadyExists) {

                    dataTransfer.items.add(file);

                }

            }


            /*
            |--------------------------------------------------------------------------
            | Update input
            |--------------------------------------------------------------------------
            */

            imageInput.files =
                dataTransfer.files;


            /*
            |--------------------------------------------------------------------------
            | Render preview
            |--------------------------------------------------------------------------
            */

            renderPreview();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | RENDER PREVIEW
    |--------------------------------------------------------------------------
    */

    function renderPreview() {

        imagePreview.innerHTML = '';


        Array
            .from(dataTransfer.files)
            .forEach(
                (file, index) => {

                    /*
                    |--------------------------------------------------------------------------
                    | WRAPPER
                    |--------------------------------------------------------------------------
                    */

                    const wrapper =
                        document.createElement('div');


                    wrapper.style.position =
                        'relative';

                    wrapper.style.border =
                        '2px solid var(--dark)';

                    wrapper.style.backgroundColor =
                        '#ffffff';

                    wrapper.style.padding =
                        '8px';

                    wrapper.style.boxSizing =
                        'border-box';

                    wrapper.style.boxShadow =
                        '3px 3px 0 var(--dark)';


                    /*
                    |--------------------------------------------------------------------------
                    | IMAGE CONTAINER
                    |--------------------------------------------------------------------------
                    */

                    const imageContainer =
                        document.createElement('div');


                    imageContainer.style.position =
                        'relative';


                    /*
                    |--------------------------------------------------------------------------
                    | IMAGE
                    |--------------------------------------------------------------------------
                    */

                    const img =
                        document.createElement('img');


                    img.style.width =
                        '100%';

                    img.style.height =
                        '120px';

                    img.style.objectFit =
                        'cover';

                    img.style.display =
                        'block';

                    img.style.borderRadius =
                        '4px';


                    /*
                    |--------------------------------------------------------------------------
                    | DELETE BUTTON
                    |--------------------------------------------------------------------------
                    */

                    const deleteButton =
                        document.createElement('button');


                    deleteButton.type =
                        'button';

                    deleteButton.innerHTML =
                        '×';

                    deleteButton.title =
                        'Hapus gambar';


                    deleteButton.style.position =
                        'absolute';

                    deleteButton.style.top =
                        '5px';

                    deleteButton.style.right =
                        '5px';

                    deleteButton.style.width =
                        '32px';

                    deleteButton.style.height =
                        '32px';

                    deleteButton.style.padding =
                        '0';

                    deleteButton.style.display =
                        'flex';

                    deleteButton.style.alignItems =
                        'center';

                    deleteButton.style.justifyContent =
                        'center';

                    deleteButton.style.backgroundColor =
                        '#ff4d4d';

                    deleteButton.style.color =
                        '#ffffff';

                    deleteButton.style.border =
                        '2px solid var(--dark)';

                    deleteButton.style.boxShadow =
                        '2px 2px 0 var(--dark)';

                    deleteButton.style.fontSize =
                        '22px';

                    deleteButton.style.fontWeight =
                        '900';

                    deleteButton.style.lineHeight =
                        '1';

                    deleteButton.style.cursor =
                        'pointer';

                    deleteButton.style.zIndex =
                        '10';


                    /*
                    |--------------------------------------------------------------------------
                    | HOVER
                    |--------------------------------------------------------------------------
                    */

                    deleteButton.addEventListener(
                        'mouseenter',
                        function () {

                            deleteButton.style.backgroundColor =
                                '#cc0000';

                        }
                    );


                    deleteButton.addEventListener(
                        'mouseleave',
                        function () {

                            deleteButton.style.backgroundColor =
                                '#ff4d4d';

                        }
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | DELETE FILE
                    |--------------------------------------------------------------------------
                    */

                    deleteButton.addEventListener(
                        'click',
                        function () {

                            const newDataTransfer =
                                new DataTransfer();


                            Array
                                .from(dataTransfer.files)
                                .forEach(
                                    (
                                        currentFile,
                                        currentIndex
                                    ) => {

                                        if (
                                            currentIndex !==
                                            index
                                        ) {

                                            newDataTransfer
                                                .items
                                                .add(
                                                    currentFile
                                                );

                                        }

                                    }
                                );


                            /*
                            |--------------------------------------------------------------------------
                            | Update DataTransfer
                            |--------------------------------------------------------------------------
                            */

                            dataTransfer.items.clear();


                            Array
                                .from(
                                    newDataTransfer.files
                                )
                                .forEach(
                                    file => {

                                        dataTransfer
                                            .items
                                            .add(file);

                                    }
                                );


                            /*
                            |--------------------------------------------------------------------------
                            | Update input
                            |--------------------------------------------------------------------------
                            */

                            imageInput.files =
                                dataTransfer.files;


                            /*
                            |--------------------------------------------------------------------------
                            | Render ulang
                            |--------------------------------------------------------------------------
                            */

                            renderPreview();

                        }
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | FILE READER
                    |--------------------------------------------------------------------------
                    */

                    const reader =
                        new FileReader();


                    reader.onload =
                        function (event) {

                            img.src =
                                event.target.result;

                        };


                    reader.readAsDataURL(file);


                    /*
                    |--------------------------------------------------------------------------
                    | IMAGE CONTAINER
                    |--------------------------------------------------------------------------
                    */

                    imageContainer.appendChild(
                        img
                    );

                    imageContainer.appendChild(
                        deleteButton
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | FILE NAME
                    |--------------------------------------------------------------------------
                    */

                    const fileName =
                        document.createElement('p');


                    fileName.textContent =
                        file.name;


                    fileName.style.margin =
                        '8px 0 5px';

                    fileName.style.fontSize =
                        '13px';

                    fileName.style.fontWeight =
                        '600';

                    fileName.style.wordBreak =
                        'break-word';


                    /*
                    |--------------------------------------------------------------------------
                    | IMAGE CAPTION
                    |--------------------------------------------------------------------------
                    */

                    const captionInput =
                        document.createElement('input');


                    captionInput.type =
                        'text';

                    captionInput.name =
                        'new_image_captions[]';

                    captionInput.className =
                        'login-input';

                    captionInput.placeholder =
                        'Caption gambar...';

                    captionInput.maxLength =
                        500;


                    captionInput.style.width =
                        '100%';

                    captionInput.style.boxSizing =
                        'border-box';

                    captionInput.style.padding =
                        '8px';


                    /*
                    |--------------------------------------------------------------------------
                    | APPEND
                    |--------------------------------------------------------------------------
                    */

                    wrapper.appendChild(
                        imageContainer
                    );

                    wrapper.appendChild(
                        fileName
                    );

                    wrapper.appendChild(
                        captionInput
                    );


                    imagePreview.appendChild(
                        wrapper
                    );

                }
            );

    }


    /*
    |--------------------------------------------------------------------------
    | FORM VALIDATION
    |--------------------------------------------------------------------------
    */

    galleryEditForm.addEventListener(
        'submit',
        function (event) {

            const caption =
                captionInput.value.trim();


            /*
            |--------------------------------------------------------------------------
            | CAPTION KOSONG
            |--------------------------------------------------------------------------
            */

            if (caption === '') {

                event.preventDefault();

                alert(
                    'Caption gallery tidak boleh kosong.'
                );

                captionInput.focus();

                return;

            }


            /*
            |--------------------------------------------------------------------------
            | CAPTION GALLERY MAX 500
            |--------------------------------------------------------------------------
            */

            if (caption.length > 500) {

                event.preventDefault();

                alert(
                    'Caption gallery maksimal 500 karakter.'
                );

                captionInput.focus();

                return;

            }


            /*
            |--------------------------------------------------------------------------
            | VALIDASI CAPTION GAMBAR LAMA
            |--------------------------------------------------------------------------
            */

            const existingCaptions =
                document.querySelectorAll(
                    'input[name^="image_captions"]'
                );


            for (const input of existingCaptions) {

                if (input.value.length > 500) {

                    event.preventDefault();

                    alert(
                        'Caption gambar maksimal 500 karakter.'
                    );

                    input.focus();

                    return;

                }

            }


            /*
            |--------------------------------------------------------------------------
            | VALIDASI CAPTION GAMBAR BARU
            |--------------------------------------------------------------------------
            */

            const newCaptions =
                document.querySelectorAll(
                    'input[name^="new_image_captions"]'
                );


            for (const input of newCaptions) {

                if (input.value.length > 500) {

                    event.preventDefault();

                    alert(
                        'Caption gambar maksimal 500 karakter.'
                    );

                    input.focus();

                    return;

                }

            }


            /*
            |--------------------------------------------------------------------------
            | VALIDASI FILE BARU
            |--------------------------------------------------------------------------
            */

            for (
                const file of dataTransfer.files
            ) {

                if (file.size > 5000000) {

                    event.preventDefault();

                    alert(
                        `Ukuran "${file.name}" `
                        + `melebihi 5 MB.`
                    );

                    return;

                }

            }

        }
    );

</script>