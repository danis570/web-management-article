<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <div class="hero-content">

            <!-- Notifikasi Error -->
            <?php if (isset($model['error'])) { ?>
                <div class="font-semibold neo-box login-card bg-primary mb-lg">
                    <?= htmlspecialchars($model['error']) ?>
                </div>
            <?php } ?>

            <?php if (isset($_SESSION['flash_message'])) { ?>

                <div class="flex items-center justify-between font-semibold neo-box login-card bg-primary mb-lg">

                    <span>
                        <?= htmlspecialchars($_SESSION['flash_message']) ?>
                    </span>

                    <a href="/" class="neo-btn">
                        Kembali
                    </a>

                </div>

                <?php unset($_SESSION['flash_message']); ?>

            <?php } ?>


            <!-- Card Profile -->
            <div class="neo-card login-card">

                <?php $profile = $model['profile']; ?>

                <form action="/profile" method="post" enctype="multipart/form-data">

                    <!-- Current Profile Image -->
                    <div class="mb-lg" style="text-align: center;">
                        <label class="font-semibold block mb-sm">
                            Foto Profile
                        </label>

                        <?php if (!empty($profile->img)) { ?>

                            <img src="<?= htmlspecialchars($profile->img) ?>" alt="Profile Image" class="neo-box" style="
                                    width: 120px;
                                    height: 120px;
                                    object-fit: cover;
                                    padding: 4px;
                                    border-radius: 8px;
                                ">

                        <?php } else { ?>

                            <div class="neo-box" style="
                                    width: 120px;
                                    height: 120px;
                                    margin: 0 auto;
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                    font-size: 2rem;
                                    font-weight: bold;
                                ">
                                <?= strtoupper(
                                    substr($profile->name, 0, 1)
                                ) ?>
                            </div>

                        <?php } ?>
                    </div>

                    <!-- Kolom Name -->
                    <div class="mb-md">
                        <label for="name" class="font-semibold block mb-sm">
                            Name
                        </label>

                        <input type="text" name="name" id="name" class="login-input" placeholder="Masukkan nama" value="<?= htmlspecialchars(
                            $_POST['name']
                            ?? $profile->name
                        ) ?>" required>
                    </div>

                    <!-- Kolom Position -->
                    <div class="mb-md">
                        <label for="position" class="font-semibold block mb-sm">
                            Position
                        </label>

                        <input type="text" name="position" id="position" class="login-input"
                            placeholder="Contoh: Ketua, Sekretaris" value="<?= htmlspecialchars(
                                $_POST['position']
                                ?? $profile->position
                            ) ?>" required>
                    </div>

                    <!-- Kolom Period -->
                    <div class="mb-md">
                        <label for="period" class="font-semibold block mb-sm">
                            Period
                        </label>

                        <input type="text" name="period" id="period" class="login-input" placeholder="Contoh: 2024-2026"
                            value="<?= htmlspecialchars(
                                $_POST['period']
                                ?? $profile->period
                            ) ?>" required>
                    </div>

                    <!-- Kolom Image -->
                    <div class="mb-lg">
                        <label for="img" class="font-semibold block mb-sm">
                            Change Profile Image
                        </label>

                        <input type="file" name="img" id="img" class="login-input" accept="image/*"
                            style="padding-top: 8px;">

                        <!-- Image Preview -->
                        <div id="preview-container" style="
                                display: none;
                                margin-top: 15px;
                                text-align: center;
                            ">
                            <p class="font-semibold mb-sm" style="font-size: 0.85rem;">
                                Pratinjau Foto Baru:
                            </p>

                            <img id="img-preview" src="#" alt="Pratinjau Foto" class="neo-box" style="
                                    max-height: 150px;
                                    width: auto;
                                    object-fit: cover;
                                    padding: 4px;
                                    border-radius: 4px;
                                ">
                        </div>
                    </div>

                    <!-- Tombol Submit -->
                    <button type="submit" class="neo-btn w-full">
                        Update Profile
                    </button>

                </form>

            </div>
        </div>
    </div>
</section>

<!-- Script Real-Time Image Preview -->
<script>
    document
        .getElementById('img')
        .addEventListener('change', function (event) {

            const file = event.target.files[0];

            const previewContainer =
                document.getElementById('preview-container');

            const imgPreview =
                document.getElementById('img-preview');

            if (file) {

                const reader = new FileReader();

                reader.onload = function (e) {
                    imgPreview.src = e.target.result;
                    previewContainer.style.display = 'block';
                };

                reader.readAsDataURL(file);

            } else {

                imgPreview.src = '#';
                previewContainer.style.display = 'none';

            }
        });
</script>