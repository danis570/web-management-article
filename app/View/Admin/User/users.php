<section class="hero">
    <div class="container">
        <div class="hero-content">
            <?php if (isset($_SESSION['flash_message'])) { ?>
                <div class="neo-box bg-success mb-md"
                    style="display: flex; justify-content: space-between; align-items: center; padding: 1rem 1.5rem; background-color: transparent; color: --var(dark); border: 2px solid var(--dark); box-shadow: 4px 4px 0px var(--dark); font-weight: bold; border-radius: 4px;">
                    <span><?= $_SESSION['flash_message']; ?></span>
                    <button onclick="this.parentElement.remove()"
                        style="background: none; border: none; color: --var(--dark); font-size: 1.2rem; cursor: pointer; font-weight: bold;">&times;</button>
                </div>
                <?php unset($_SESSION['flash_message']);
            } ?>
            <div class="hero-text">
                <div class="article-header" style="display: flex; justify-content: space-between; align-items: center;">
                    <h3>
                        Semua <span class="highlight highlight-yellow">User</span>
                    </h3>

                    <!-- Kontainer flex untuk tombol add dan kolom search -->
                    <div class="search-container" style="display: flex; align-items: center; gap: 1rem;">
                        <a href="/register" class="neo-btn neo-btn-primary neo-btn-sm">Add</a>
                        <div class="article-search">
                            <input type="search" id="user-search" placeholder="Cari user...">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container" style="margin-top: 3.5rem;">
        <div class="component-example">

            <?php if (!isset($model['error']) && isset($model['user']) && !empty($model['user'])) { ?>

                <div class="grid grid-cols-4 gap-grid-md mt-md" id="user-list" style="align-items: start;">
                    <?php foreach ($model['user'] as $user) { ?>

                        <div class="neo-card user-card" data-name="<?= strtolower($user['name']) ?>">
                            <?php if ($user['role'] == 'admin') { ?>

                                <!-- Tampilan Card Admin -->
                                <div class="user-info">
                                    <h4 class="user-title"><?= $user['name'] ?></h4>
                                </div>
                                <div class="admin-footer">
                                    <span class="badge-admin">Administrator</span>
                                </div>

                            <?php } else { ?>

                                <!-- Tampilan Card User Reguler -->
                                <div class="user-header">
                                    <div class="user-avatar">
                                        <img src="<?= (!empty($user['img'])) ? $user['img'] : '/uploads/user-img/default-img-user.png' ?>"
                                            alt="img-user-<?= (!empty($user['img'])) ? $user['img'] : 'default-img-user.png' ?>">
                                    </div>
                                    <h4 class="user-title"><?= $user['name'] ?></h4>
                                </div>
                                <div class="user-details">
                                    <p class="user-position"><?= $user['position'] ?></p>
                                    <p class="user-period">Periode: <?= $user['period'] ?></p>
                                </div>

                            <?php } ?>
                        </div>

                    <?php } ?>
                </div>

                <!-- Pesan jika pencarian user tidak ditemukan -->
                <div id="no-result" style="display: none;" class="text-center mt-md">
                    <p>User tidak ditemukan.</p>
                </div>

            <?php } else { ?>

                <div class="text-center mt-md">
                    <p><?= isset($model['error']) ? $model['error'] : 'Belum ada data user.' ?></p>
                </div>

            <?php } ?>

        </div>
    </div>
</section>

<style>
    /* --- USER MANAGEMENT CARDS (NEO-BRUTALISM) --- */

    #user-list {
        row-gap: 1.5rem !important;
    }

    #user-list .user-card {
        margin-bottom: 1.5rem;
    }

    /* Struktur utama kartu user */
    .neo-card.user-card {
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        padding: 1.5rem;
        height: auto;
        /* DIUBAH dari 100% menjadi auto agar tinggi kotak seukuran konten */
        min-height: unset;
        /* DIUBAH dari 220px menjadi unset agar tidak memaksa batas minimal tinggi */
        box-sizing: border-box;
        transition: all 0.3s ease;
    }

    /* Judul Nama */
    .user-card .user-title {
        margin: 0;
        font-size: 1.2rem;
        font-weight: 800;
        color: var(--dark);
        line-height: 1.3;
    }

    /* Kontainer Profil Utama User Reguler (Foto + Nama) */
    .user-card .user-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 0.75rem;
    }

    /* Kotak Foto Profil Bergaya Brutalism */
    .user-card .user-avatar {
        flex-shrink: 0;
        width: 55px;
        height: 55px;
        border: 2px solid var(--dark);
        box-shadow: 2px 2px 0px var(--dark);
        border-radius: 4px;
        overflow: hidden;
        background-color: #ffffff;
    }

    .user-card .user-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Blok Detail (Garis Putus-putus menempel rapat ke bawah Nama) */
    .user-card .user-details {
        display: flex;
        flex-direction: column;
        gap: 0.35rem;
        border-top: 2px dashed var(--dark);
        padding-top: 0.75rem;
        margin-top: 0.5rem;
    }

    .user-card .user-position {
        margin: 0;
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--dark);
    }

    .user-card .user-period {
        margin: 0;
        font-size: 0.85rem;
        font-weight: 500;
        color: var(--dark);
    }

    .user-card .admin-footer {
        margin-top: 0.5rem;
        /* Menggantikan margin-top: auto */
        padding-top: 0;
    }

    /* Desain Badge Kuning Admin */
    .user-card .badge-admin {
        display: inline-block;
        padding: 0.35rem 0.85rem;
        font-size: 0.8rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-radius: 4px;
        border: 2px solid var(--dark);
        box-shadow: 2px 2px 0px var(--dark);
        background-color: var(--yellow-light);
        color: var(--dark);
    }

    /* Efek hover dinamis ala Neo-brutalism */
    .neo-card.user-card:hover {
        transform: translateY(-4px);
        box-shadow: 6px 6px 0px var(--dark);
    }

    /* Efek animasi transisi halus saat kartu user disembunyikan */
    .user-card.is-hidden {
        opacity: 0 !important;
        transform: scale(0) !important;
        position: absolute !important;
        pointer-events: none !important;
        transition: all 0.3s ease;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('user-search');
        const users = document.querySelectorAll('.user-card');
        const noResult = document.getElementById('no-result');

        if (!searchInput || users.length === 0) {
            return;
        }

        searchInput.addEventListener('input', function () {
            const keyword = this.value.toLowerCase().trim();
            let found = false;

            users.forEach(user => {
                // KUNCI PERBAIKAN: Mengambil seluruh teks di dalam elemen kartu (Nama, Jabatan, & Periode)
                const fullText = user.textContent.toLowerCase();

                if (fullText.includes(keyword)) {
                    user.classList.remove('is-hidden'); // Tampilkan kartu user jika cocok
                    found = true;
                } else {
                    user.classList.add('is-hidden');    // Sembunyikan kartu user jika tidak cocok
                }
            });

            // Menampilkan pesan jika tidak ada teks yang cocok sama sekali
            if (noResult) {
                noResult.style.display = found ? 'none' : 'block';
            }
        });
    });
</script>