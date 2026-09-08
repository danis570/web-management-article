<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <div class="hero-content">

            <!-- Notifikasi Error (Menggunakan styling neo-box) -->
            <?php if (isset($model['error'])) { ?>
                <div class="font-semibold neo-box login-card bg-primary mb-lg">
                    <?= $model['error'] ?>
                </div>
            <?php } ?>

            <!-- Card Register (Menggunakan class yang sama dengan login) -->
            <div class="neo-card login-card">

                <div class="text-center mb-lg">
                    <h3><?= $model['title'] ?? 'Register' ?></h3>
                    <p class="mt-sm">
                        Silakan isi data untuk mendaftarkan user baru.
                    </p>
                </div>

                <form action="/register" method="post" enctype="multipart/form-data">

                    <!-- Kolom Name -->
                    <div class="mb-md">
                        <label for="name" class="font-semibold block mb-sm">Name</label>
                        <input type="text" name="name" id="name" class="login-input" placeholder="Masukkan nama"
                            value="<?= $_POST['name'] ?? '' ?>" required>
                    </div>

                    <!-- Kolom Position -->
                    <div class="mb-md">
                        <label for="position" class="font-semibold block mb-sm">Position</label>
                        <input type="text" name="position" id="position" class="login-input"
                            placeholder="Contoh: Ketua, Sekretaris" value="<?= $_POST['position'] ?? '' ?>" required>
                    </div>

                    <!-- Kolom Period -->
                    <div class="mb-md">
                        <label for="period" class="font-semibold block mb-sm">Period</label>
                        <input type="text" name="period" id="period" class="login-input" placeholder="Contoh: 2024-2026"
                            value="<?= $_POST['period'] ?? '' ?>" required>
                    </div>

                    <!-- Kolom Role -->
                    <div class="mb-md">
                        <label for="role" class="font-semibold block mb-sm">Role</label>
                        <select name="role" id="role" class="login-input" style="width: 100%; cursor: pointer;"
                            required>
                            <option value="user" selected>User</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>

                    <!-- Kolom Email -->
                    <div class="mb-md">
                        <label for="email" class="font-semibold block mb-sm">Email</label>
                        <input type="email" name="email" id="email" class="login-input" placeholder="Masukkan email"
                            value="<?= $_POST['email'] ?? '' ?>" required>
                    </div>

                    <!-- Kolom Password -->
                    <div class="mb-md">
                        <label for="password" class="font-semibold block mb-sm">Password</label>
                        <input type="password" name="password" id="password" class="login-input"
                            placeholder="Masukkan password" value="<?= $_POST['password'] ?? '' ?>" required>
                    </div>

                    <!-- Kolom Image (Dengan Fitur Preview) -->
                    <div class="mb-lg">
                        <label for="img" class="font-semibold block mb-sm">Profile Image</label>
                        <input type="file" name="img" id="img" class="login-input" accept="image/*"
                            style="padding-top: 8px;">

                        <!-- Kontainer untuk Image Preview -->
                        <div id="preview-container" style="display: none; margin-top: 15px; text-align: center;">
                            <p class="font-semibold mb-sm" style="font-size: 0.85rem;">Pratinjau Foto:</p>
                            <img id="img-preview" src="#" alt="Pratinjau Foto" class="neo-box"
                                style="max-height: 150px; width: auto; object-fit: cover; padding: 4px; border-radius: 4px;">
                        </div>
                    </div>

                    <!-- Tombol Submit -->
                    <button type="submit" class="neo-btn w-full">
                        Register
                    </button>

                </form>

            </div>
        </div>
    </div>
</section>

<!-- Script Real-Time Image Preview -->
<script>
    document.getElementById('img').addEventListener('change', function (event) {
        const file = event.target.files[0];
        const previewContainer = document.getElementById('preview-container');
        const imgPreview = document.getElementById('img-preview');

        if (file) {
            const reader = new FileReader();

            reader.onload = function (e) {
                imgPreview.src = e.target.result;
                previewContainer.style.display = 'block'; // Tampilkan pratinjau
            }

            reader.readAsDataURL(file);
        } else {
            imgPreview.src = '#';
            previewContainer.style.display = 'none'; // Sembunyikan jika dibatalkan
        }
    });
</script>