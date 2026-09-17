<!-- // Decorative hero -->
<style>
    /* Container Utama */
    .decorative-hero {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 20px;
        padding: 0;
    }

    /* Desain Kotak Kuning-Hijau (Badge Brutalist) */
    .badge-brutal {
        background-color: var(--yellow-light);
        color: #000000;
        font-family: monospace;
        font-weight: bold;
        font-size: 0.9rem;
        padding: 8px 16px;
        border: 3px solid #000000;
        box-shadow: 4px 4px 0px #000000;
        display: flex;
        align-items: center;
        gap: 8px;
        letter-spacing: 1px;
    }

    .badge-brutal .dot {
        font-size: 1.2rem;
    }

    /* Gaya Teks Judul dan Link */
    .section-title {
        margin: 0;
        padding: 0;
        font-size: 1rem;
        border-bottom: 2px solid var(--dark);
        font-weight: 600;
    }

    .typing-link {
        color: var(--dark);
        text-decoration: none;
        display: inline;
    }

    /* Mengubah warna teks menjadi biru saat diarahkan kursor */
    .typing-link:hover {
        color: var(--primary);
    }

    /* Animasi Kursor Berkedip (_) */
    .cursor {
        color: var(--dark);
        font-size: 1rem;
        font-weight: 900;
        display: inline-block;
        animation: blink 0.8s infinite;
        margin-left: -5px;
    }

    @keyframes blink {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: 0;
        }
    }
</style>

<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <div class="hero-content">
            <div class="decorative-hero">
                <!-- Kotak label atas -->
                <div class="badge-brutal">
                    <span class="dot">•</span> BERITA TERBARU, Klik untuk membuka
                </div>

                <!-- Judul Utama + Link Aktif Berganti Teks -->
                <h2 class="section-title">
                    <!-- Tambahkan id="typing-link" agar bisa dibaca oleh JavaScript -->
                    <a href="link-ke-artikel-terbaru.php" id="typing-link" class="typing-link"></a>
                    <!-- Kursor diletakkan di luar tag <a> agar tidak ikut ter-klik -->
                    <span class="cursor">_</span>
                </h2>
            </div>
            <div class="hero-text">
                <h1>PR IPNU & IPPNU <span class="highlight highlight-yellow">Desa Ketambul</span></h1>
                <p>Website resmi Pimpinan Ranting (PR) Ikatan Pelajar Nahdlatul Ulama (IPNU) dan Ikatan Pelajar Putri
                    Nahdlatul Ulama (IPPNU) Desa Ketambul.</p>
                <div class="neo-button-group">
                    <a href="#components" class="neo-btn">Struktur Organisasi</a>
                    <a href="#examples" class="neo-btn neo-btn-secondary">Kegiatan Terdekat</a>
                </div>
            </div>
            <div class="hero-animation">

            </div>
        </div>
    </div>
</section>

<!-- // Decorative hero -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Tulis daftar teks yang ingin Anda tampilkan secara bergantian di sini
        const words = ["Rapat pengurus 2026 berjalan di aula", "tips membaca dengan cepat", "judul artikel akan di render", "newx ketua akan memberikan intruksi"];

        let wordIndex = 0;
        let charIndex = 0;
        let isDeleting = false;
        const typingSpeed = 50; // Kecepatan mengetik (milidetik per huruf)
        const erasingSpeed = 20;  // Kecepatan menghapus (milidetik per huruf)
        const delayBetweenWords = 2000; // Jeda waktu diam saat teks selesai diketik

        const typingElement = document.getElementById('typing-link');

        function typeEffect() {
            const currentWord = words[wordIndex];

            if (isDeleting) {
                // Menghapus huruf satu per satu
                typingElement.textContent = currentWord.substring(0, charIndex - 1);
                charIndex--;
            } else {
                // Menambah huruf satu per satu
                typingElement.textContent = currentWord.substring(0, charIndex + 1);
                charIndex++;
            }

            // Atur delay dinamis tergantung kondisi (mengetik atau menghapus)
            let currentSpeed = isDeleting ? erasingSpeed : typingSpeed;

            // Jika kata sudah selesai diketik sepenuhnya
            if (!isDeleting && charIndex === currentWord.length) {
                currentSpeed = delayBetweenWords; // Berikan jeda diam sebentar
                isDeleting = true;
            }
            // Jika kata sudah terhapus habis sepenuhnya
            else if (isDeleting && charIndex === 0) {
                isDeleting = false;
                wordIndex = (wordIndex + 1) % words.length; // Pindah ke kata berikutnya di dalam array
                currentSpeed = 500; // Jeda sebelum mulai mengetik kata baru
            }

            setTimeout(typeEffect, currentSpeed);
        }

        // Jalankan fungsi animasi pertama kali jika elemen ditemukan
        if (typingElement) {
            typeEffect();
        }
    });
</script>

<!-- Images -->
<section id="components" class="hero-photos">

    <div class="container">

        <div class="component-example">

            <!-- HEADER -->
            <div class="dummy-articles-header">

                <h3>
                    <span class="highlight highlight-yellow">
                        Foto Kegiatan Terbaru
                    </span>
                </h3>

                <p class="dummy-articles-subtitle">
                    Dokumentasi kegiatan IPNU & IPPNU Desa Ketambul
                </p>

            </div>


            <!-- GRID FOTO -->
            <div class="grid grid-cols-2 gap-grid-md mt-md">
                <img src="/1.png" alt="Placeholder" class="neo-image">
                <img src="/2.png" alt="Placeholder" class="neo-image">
            </div>


            <!-- CTA "Lihat Semua Foto" -->
            <div class="dummy-articles-footer">
                <a href="/gallery" class="neo-btn">
                    Lihat Semua Foto
                </a>
            </div>

        </div>

    </div>

</section>

<!-- =========================================================
     DUMMY ARTICLES
========================================================= -->
<section id="articles" class="dummy-articles">

    <div class="container">

        <!-- HEADER -->
        <div class="dummy-articles-header">

            <h3>
                <span class="highlight highlight-yellow">
                    Artikel Terbaru
                </span>
            </h3>

            <p class="dummy-articles-subtitle">
                Berita dan informasi terbaru dari IPNU & IPPNU Desa Ketambul
            </p>

        </div>


        <!-- GRID -->
        <div class="grid grid-cols-3 gap-grid-md dummy-articles-grid">

            <!-- DUMMY 1 -->
            <a href="#" class="neo-card article-card">

                <div class="article-image">
                    <img src="/1.png" alt="Dummy Artikel 1" loading="lazy">
                </div>

                <div class="article-content">

                    <div class="article-header-box">
                        <h4 class="article-title">
                            Rapat Pengurus 2026 Berjalan Lancar di Aula Desa
                        </h4>
                    </div>

                    <div class="article-author-info">
                        <span class="article-author-name">Admin IPNU</span>
                        <span class="article-date">13 Sep 2026</span>
                        <span class="article-view-count">👁 24</span>
                    </div>

                </div>

            </a>


            <!-- DUMMY 2 -->
            <a href="#" class="neo-card article-card">

                <div class="article-image">
                    <img src="/2.png" alt="Dummy Artikel 2" loading="lazy">
                </div>

                <div class="article-content">

                    <div class="article-header-box">
                        <h4 class="article-title">
                            Tips Membaca Cepat untuk Pelajar & Santri
                        </h4>
                    </div>

                    <div class="article-author-info">
                        <span class="article-author-name">Admin IPPNU</span>
                        <span class="article-date">12 Sep 2026</span>
                        <span class="article-view-count">👁 18</span>
                    </div>

                </div>

            </a>


            <!-- DUMMY 3 -->
            <a href="#" class="neo-card article-card">

                <div class="article-image">
                    <img src="/1.png" alt="Dummy Artikel 3" loading="lazy">
                </div>

                <div class="article-content">

                    <div class="article-header-box">
                        <h4 class="article-title">
                            Ketua Baru Akan Memberikan Instruksi Minggu Depan
                        </h4>
                    </div>

                    <div class="article-author-info">
                        <span class="article-author-name">Admin</span>
                        <span class="article-date">11 Sep 2026</span>
                        <span class="article-view-count">👁 42</span>
                    </div>

                </div>

            </a>


            <!-- DUMMY 4 -->
            <a href="#" class="neo-card article-card">

                <div class="article-image">
                    <img src="/2.png" alt="Dummy Artikel 4" loading="lazy">
                </div>

                <div class="article-content">

                    <div class="article-header-box">
                        <h4 class="article-title">
                            Kegiatan Sosial IPNU & IPPNU di Bulan September
                        </h4>
                    </div>

                    <div class="article-author-info">
                        <span class="article-author-name">Admin IPNU</span>
                        <span class="article-date">10 Sep 2026</span>
                        <span class="article-view-count">👁 15</span>
                    </div>

                </div>

            </a>


            <!-- DUMMY 5 -->
            <a href="#" class="neo-card article-card">

                <div class="article-image">
                    <img src="/1.png" alt="Dummy Artikel 5" loading="lazy">
                </div>

                <div class="article-content">

                    <div class="article-header-box">
                        <h4 class="article-title">
                            Pelatihan Kepemimpinan untuk Anggota Baru
                        </h4>
                    </div>

                    <div class="article-author-info">
                        <span class="article-author-name">Admin IPPNU</span>
                        <span class="article-date">09 Sep 2026</span>
                        <span class="article-view-count">👁 30</span>
                    </div>

                </div>

            </a>


            <!-- DUMMY 6 -->
            <a href="#" class="neo-card article-card">

                <div class="article-image">
                    <img src="/2.png" alt="Dummy Artikel 6" loading="lazy">
                </div>

                <div class="article-content">

                    <div class="article-header-box">
                        <h4 class="article-title">
                            Jadwal Kegiatan Rutin Bulan Ini
                        </h4>
                    </div>

                    <div class="article-author-info">
                        <span class="article-author-name">Admin</span>
                        <span class="article-date">08 Sep 2026</span>
                        <span class="article-view-count">👁 12</span>
                    </div>

                </div>

            </a>

        </div>


        <!-- CTA "Lihat Semua" -->
        <div class="dummy-articles-footer">
            <a href="/article" class="neo-btn">
                Lihat Semua Artikel
            </a>
        </div>

    </div>

</section>

<style>
    /* =========================================================
   HERO PHOTOS ("Foto Kegiatan Terbaru")
========================================================= */

    .hero-photos {
        padding-top: 60px;
        padding-bottom: 60px;
    }

    /* =========================================================
   DUMMY ARTICLES
========================================================= */

    .dummy-articles {
        padding-top: 60px;
        padding-bottom: 80px;
    }


    /* =========================
   HEADER
========================= */

    .dummy-articles-header {
        margin-bottom: 32px;
        padding-bottom: 16px;
        border-bottom: 3px solid var(--dark);
    }

    .dummy-articles-header h3 {
        margin: 0 0 6px 0;
        font-size: 1.8rem;
        font-weight: 800;
    }

    .dummy-articles-subtitle {
        margin: 0;
        font-size: 0.9rem;
        color: #666;
        font-weight: 500;
    }


    /* =========================
   GRID
========================= */

    .dummy-articles-grid {
        margin-bottom: 40px;
    }


    /* =========================
   FOOTER / CTA
========================= */

    .dummy-articles-footer {
        text-align: center;
        margin-top: 60px;
    }


    /* =========================
   RESPONSIVE
========================= */

    @media (max-width: 992px) {

        .dummy-articles-grid.grid-cols-3 {
            grid-template-columns: repeat(2, 1fr) !important;
        }

    }

    @media (max-width: 576px) {

        .dummy-articles {
            padding-top: 40px;
            padding-bottom: 60px;
        }

        .dummy-articles-header h3 {
            font-size: 1.4rem;
        }

        .dummy-articles-grid.grid-cols-3 {
            grid-template-columns: 1fr !important;
        }

    }
</style>