<section class="hero">
    <div class="container">
        <div class="hero-content"> <?php if (isset($_SESSION['flash_message'])) { ?>
                <div class="neo-box bg-success mb-md"
                    style="display: flex; justify-content: space-between; align-items: center; padding: 1rem 1.5rem; background-color: transparent; color: --var(dark); border: 2px solid var(--dark); box-shadow: 4px 4px 0px var(--dark); font-weight: bold; border-radius: 4px;">
                    <span> <?= htmlspecialchars($_SESSION['flash_message']) ?> </span> <button
                        onclick="this.parentElement.remove()"
                        style="background: none; border: none; color: --var(--dark); font-size: 1.2rem; cursor: pointer; font-weight: bold;">
                        &times; </button>
                </div> <?php unset($_SESSION['flash_message']); ?> <?php } ?>
            <div class="hero-text">
                <div class="article-header" style="display: flex; justify-content: space-between; align-items: center;">
                    <h3> Semua <span class="highlight highlight-yellow">User</span> </h3>
                    <!-- Kontainer flex untuk tombol add dan kolom search -->
                    <div class="search-container" style="display: flex; align-items: center; gap: 1rem;"> <a
                            href="/register" class="neo-btn neo-btn-primary neo-btn-sm"> Add </a>
                        <div class="article-search"> <input type="search" id="user-search" placeholder="Cari user...">
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

                        <div class="neo-card user-card" data-name="<?= strtolower(htmlspecialchars($user['name'])) ?>">

                            <?php if ($user['role'] == 'admin') { ?>

                                <!-- CARD ADMIN -->
                                <div class="user-info">
                                    <h4 class="user-title">
                                        <?= htmlspecialchars($user['name']) ?>
                                    </h4>
                                </div>

                                <div class="admin-footer">
                                    <span class="badge-admin">
                                        Administrator
                                    </span>
                                </div>

                            <?php } else { ?>

                                <!-- CARD USER -->
                                <div class="user-header">

                                    <div class="user-avatar">
                                        <img src="/uploads/users/<?= !empty($user['img'])
                                            ? htmlspecialchars($user['img'])
                                            : 'default.png' ?>" alt="img-user">
                                    </div>

                                    <h4 class="user-title">
                                        <?= htmlspecialchars($user['name']) ?>
                                    </h4>

                                </div>

                                <!-- RESET PASSWORD -->
                                <div class="user-actions" style="
                    margin-top: 1rem;
                    padding-top: 1rem;
                    border-top: 2px dashed var(--dark);
                ">

                                    <form id="form-reset-<?= (int) $user['id'] ?>" action="/user/reset-password" method="POST">
                                        <input type="hidden" name="user_id" value="<?= (int) $user['id'] ?>">

                                        <button type="button" class="neo-btn neo-btn-sm" onclick="openNeoModal(
                            '<?= htmlspecialchars($user['name'], ENT_QUOTES) ?>',
                            'form-reset-<?= (int) $user['id'] ?>'
                        )" style="
                            width: 100%;
                            background-color: var(--yellow-light);
                            color: var(--dark);
                            border: 2px solid var(--dark);
                            box-shadow: 3px 3px 0 var(--dark);
                            font-weight: bold;
                            cursor: pointer;
                            padding: 8px 16px;
                        ">
                                            Reset Password
                                        </button>
                                    </form>

                                </div>

                            <?php } ?>

                        </div>

                    <?php } ?>

                    <!-- ========================================================= -->
                    <!-- SATU MODAL SAJA -->
                    <!-- ========================================================= -->

                    <div id="neoModalOverlay" class="neo-modal-overlay" style="display: none;">
                        <div class="neo-modal-box">

                            <div class="neo-modal-header">
                                PERHATIAN!
                            </div>

                            <div class="neo-modal-body">
                                Apakah Anda yakin ingin mereset password untuk user
                                <span id="neoModalTargetUser" style="
                    text-decoration: underline;
                    font-weight: 900;
                "></span>?
                            </div>

                            <div class="neo-modal-footer">

                                <button type="button" class="neo-btn-modal btn-cancel" onclick="closeNeoModal()">
                                    Batal
                                </button>

                                <button type="button" class="neo-btn-modal btn-confirm" id="neoModalSubmitBtn">
                                    Ya, Reset!
                                </button>

                            </div>

                        </div>
                    </div>
                </div> <!-- Pesan jika pencarian user tidak ditemukan -->
                <div id="no-result" style="display: none;" class="text-center mt-md">
                    <p>User tidak ditemukan.</p>
                </div> <?php } else { ?>
                <div class="text-center mt-md">
                    <p> <?= isset($model['error']) ? htmlspecialchars($model['error']) : 'Belum ada data user.' ?> </p>
                </div> <?php } ?>
        </div>
    </div>
</section>

<style>
    /* Overlay Background */
    .neo-modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.4);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        backdrop-filter: blur(2px);
    }

    /* Kotak Modal Utama */
    .neo-modal-box {
        background-color: #ffffff;
        border: 4px solid #000000;
        box-shadow: 8px 8px 0px #000000;
        width: 90%;
        max-width: 400px;
        padding: 0;
        transform: rotate(-1deg);
        /* Efek miring khas neo-brutalism */
        transition: transform 0.2s ease;
    }

    .neo-modal-box:hover {
        transform: rotate(0deg);
    }

    /* Header Pop-up */
    .neo-modal-header {
        background-color: #ff5c5c;
        /* Merah kontras */
        color: #000000;
        border-bottom: 4px solid #000000;
        padding: 12px;
        font-size: 1.2rem;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    /* Isi Pesan */
    .neo-modal-body {
        padding: 24px;
        font-size: 1rem;
        font-weight: 700;
        color: #000000;
        line-height: 1.5;
        background-color: #fbeee0;
    }

    /* Footer / Area Tombol */
    .neo-modal-footer {
        padding: 16px;
        display: flex;
        gap: 12px;
        justify-content: flex-end;
        border-top: 4px solid #000000;
        background-color: #ffffff;
    }

    /* Tombol di dalam Modal */
    .neo-btn-modal {
        padding: 10px 20px;
        font-family: inherit;
        font-weight: 900;
        font-size: 0.9rem;
        text-transform: uppercase;
        cursor: pointer;
        border: 3px solid #000000;
        background-color: #ffffff;
        box-shadow: 4px 4px 0px #000000;
        transition: all 0.1s ease;
    }

    .neo-btn-modal:active {
        transform: translate(4px, 4px);
        box-shadow: 0px 0px 0px #000000;
    }

    .neo-btn-modal.btn-cancel {
        background-color: #e0e0e0;
    }

    .neo-btn-modal.btn-confirm {
        background-color: #38ef7d;
        /* Hijau Terang mendominasi */
    }

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
    let activeFormId = null;

    function openNeoModal(username, formId) {
        activeFormId = formId;

        // Set teks nama user tujuan ke dalam modal
        document.getElementById('neoModalTargetUser').innerText = username;

        // Tampilkan modal overlay
        const overlay = document.getElementById('neoModalOverlay');
        overlay.style.display = 'flex';

        // Hubungkan tombol konfirmasi modal ke form yang sedang aktif
        document.getElementById('neoModalSubmitBtn').onclick = function () {
            if (activeFormId) {
                document.getElementById(activeFormId).submit();
            }
        };
    }

    function closeNeoModal() {
        document.getElementById('neoModalOverlay').style.display = 'none';
        activeFormId = null;
    }

    // Opsional: Tutup modal saat user mengklik area luar kotak hitam modal
    window.onclick = function (event) {
        const overlay = document.getElementById('neoModalOverlay');
        if (event.target === overlay) {
            closeNeoModal();
        }
    }

</script>