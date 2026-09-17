<!-- Footer -->
<footer class="footer" style="border-top: 2px dashed var(--dark);">
    <div class="container" >
        <div class="footer-content">
            <!-- Kolom Kiri: Logo & Deskripsi -->
            <div class="footer-column footer-brand">
                <a href="/logo.webp" class="footer-logo">
                    <!-- Ganti src dengan logo bundar Anda jika ada -->
                    <div class="footer-logo-icon">
                        <img src="/logo.webp" alt="Logo"
                            style="width:100%; height:100%; border-radius:50%;">
                    </div>
                    PR IPNU & IPPNU Desa Ketambul
                </a>
                <p>Jelajahi seluruh kontent tentang PR IPNU & IPPNU Desa Ketambul, Website ini di kelola langsung oleh Departemen Komunikasi.</p>
            </div>

            <!-- Kolom Tengah: Navigasi -->
            <div class="footer-column">
                <h3>PROFILE</h3>
                <ul class="footer-links">
                    <li><a href="#">Pengurus Aktif</a></li>
                    <li><a href="#">Alumni</a></li>
                    <li><a href="#">Sejarah</a></li>
                    <li><a href="#">Kegiatan</a></li>
                </ul>
            </div>

            <!-- Kolom Kanan: Sosial Media -->
            <div class="footer-column">
                <h3>IKUTI KAMI</h3>
                <ul class="footer-links">
                    <li><a href="#">YouTube</a></li>
                    <li><a href="#">Instagram</a></li>
                    <li><a href="#">TikTok</a></li>
                    <li><a href="#">Facebook</a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Garis pembatas solid memanjang penuh layar -->
    <div class="footer-divider"></div>

    <div class="container">
        <div class="footer-bottom">
            <p>&copy; 2026 PR IPNU & IPPNU Desa Ketambul.</p>
        </div>
    </div>
</footer>


<!-- Mobile menu script -->
<script>
document.addEventListener('DOMContentLoaded', function () {

    const menuBtn    = document.getElementById('menuBtn');
    const menuClose  = document.getElementById('menuClose');
    const mobileMenu = document.getElementById('mobileMenu');

    if (!menuBtn || !mobileMenu) return;

    function openMenu() {
        mobileMenu.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeMenu() {
        mobileMenu.classList.remove('active');
        document.body.style.overflow = '';
    }

    menuBtn.addEventListener('click', openMenu);
    menuClose.addEventListener('click', closeMenu);

    // Tutup kalau klik link
    mobileMenu.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', closeMenu);
    });

    // Tutup dengan ESC
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeMenu();
    });

});
</script>

</body>

</html>