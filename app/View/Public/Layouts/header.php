<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $model['title'] ?></title>
    <link rel="stylesheet" href="/assets/neo-brutalism-css/styles/main.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&family=Space+Grotesk:wght@400;700&display=swap"
        rel="stylesheet">
</head>

<body>
<!-- Navigation -->
<nav class="navbar">
    <div class="container nav-container">

        <!-- LOGO -->
        <a href="/" class="logo">
            <div class="logo-icon">PR</div>
            <span class="logo-text">IPNU & IPPNU Desa Ketambul</span>
        </a>

        <!-- DESKTOP MENU -->
        <ul class="menu">
            <li>
                <a href="/article" class="<?= ($model['current'] ?? '') == 'article' ? 'active' : '' ?>">
                    Artikel
                </a>
            </li>
            <li><a href="/gallery">Galleri</a></li>
            <li><a href="/user">Pengguna</a></li>
            <li>
                <a href="/login" class="<?= ($model['current'] ?? '') == 'login' ? 'active' : '' ?>">
                    Login
                </a>
            </li>
        </ul>

        <!-- MOBILE MENU BUTTON -->
        <button class="mobile-menu-button" id="menuBtn" aria-label="Buka menu" aria-expanded="false">
            ☰
        </button>

    </div>
</nav>


<!-- =========================================================
     MOBILE MENU OVERLAY
========================================================= -->
<div class="mobile-menu-overlay" id="mobileMenu" aria-hidden="true">

    <!-- HEADER — SAMA PERSIS DENGAN NAVBAR -->
    <div class="navbar mobile-menu-navbar">
        <div class="container nav-container">

            <a href="/" class="logo">
                <div class="logo-icon">PR</div>
                <span class="logo-text">IPNU & IPPNU Desa Ketambul</span>
            </a>

            <button type="button"
                    class="mobile-menu-button"
                    id="menuClose"
                    aria-label="Tutup menu">
                &times;
            </button>

        </div>
    </div>


    <!-- BODY: Menu List -->
    <div class="mobile-menu-body">

        <div class="mobile-menu-label">MENU UTAMA</div>

        <ul class="mobile-menu-list">

            <li>
                <a href="/article"
                   class="mobile-menu-item <?= ($model['current'] ?? '') == 'article' ? 'mobile-menu-item--active' : '' ?>">
                    ARTIKEL
                </a>
            </li>

            <li>
                <a href="/gallery"
                   class="mobile-menu-item <?= ($model['current'] ?? '') == 'gallery' ? 'mobile-menu-item--active' : '' ?>">
                    GALLERI
                </a>
            </li>

            <li>
                <a href="/user"
                   class="mobile-menu-item <?= ($model['current'] ?? '') == 'user' ? 'mobile-menu-item--active' : '' ?>">
                    PENGGUNA
                </a>
            </li>

            <?php if ($model['isLoggedIn'] ?? false): ?>

                <li>
                    <a href="/logout" class="mobile-menu-item">
                        KELUAR
                    </a>
                </li>

            <?php else: ?>

                <li>
                    <a href="/login"
                       class="mobile-menu-item <?= ($model['current'] ?? '') == 'login' ? 'mobile-menu-item--active' : '' ?>">
                        LOGIN
                    </a>
                </li>

            <?php endif; ?>

        </ul>

    </div>

</div>