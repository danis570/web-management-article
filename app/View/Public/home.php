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
                    <a href="#components" class="neo-btn">Pengurus Aktif</a>
                    <a href="#examples" class="neo-btn neo-btn-secondary">Kegiatan Terdekat</a>
                </div>
            </div>
            <div class="hero-animation">
                <div class="shape1 pulse-shape"
                    style="position: absolute; width: 80px; height: 80px; background-color: var(--primary); border: 4px solid var(--dark); top: 20%; left: 30%;">
                </div>
                <div class="shape2"
                    style="position: absolute; width: 120px; height: 120px; background-color: var(--yellow); border: 4px solid var(--dark); bottom: 20%; right: 20%;">
                </div>
                <div class="shape3"
                    style="position: absolute; width: 60px; height: 60px; background-color: var(--accent); border: 4px solid var(--dark); bottom: 30%; left: 20%;">
                </div>
                <div class="rotate-circle"
                    style="position: absolute; width: 100px; height: 100px; border: 4px solid var(--dark); border-radius: 50%; top: 30%; right: 30%;">
                </div>
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
<section id="components" style="margin-bottom: 4rem;">
    <div class="container">
        <div class="component-example">
            <h3>Foto Kegiatan Terbaru</h3>
            <div class="grid grid-cols-2 gap-grid-md mt-md">
                <img src="/1.png" alt="Placeholder" class="neo-image">
                <img src="/2.png" alt="Placeholder" class="neo-image">
            </div>
        </div>
    </div>
</section>