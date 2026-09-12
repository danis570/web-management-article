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
            <a href="/" class="logo">
                <div class="logo-icon">PR</div>
                IPNU & IPPNU Desa Ketambul
            </a>

            <!-- Menu Utama / Sisi Kanan -->
            <div class="nav-right-section" style="position: relative; display: flex; align-items: center; gap: 1rem;">

                <!-- Tombol Foto Profil Pemicu Dropdown -->
                <div class="profile-dropdown-container">
                    <button id="profileMenuBtn" style="
                    background: none;
                    border: none;
                    padding: 0;
                    cursor: pointer;
                    display: flex;
                    align-items: center;
                    outline: none;
                ">
                        <img src="/uploads/users/<?= !empty($model['authProfile']['img']) ? htmlspecialchars($model['authProfile']['img']) : 'default.png' ?>"
                            alt="Profile" style="
                            width: 40px;
                            height: 40px;
                            border-radius: 50%;
                            object-fit: cover;
                            border: 2px solid #000;
                            box-shadow: 2px 2px 0px #000;
                         ">
                    </button>

                    <!-- KOTAK DROPDOWN MENU (Gaya Neo-Brutalism mirip gambar) -->
                    <div id="profileDropdown" class="dropdown-menu" style="
                                display: none;
                                position: absolute;
                                top: 120%;
                                right: 0;
                                width: 260px;
                                background-color: var(--main-bg);
                                border: 3px solid #000;
                                box-shadow: 4px 4px 0px #000;
                                border-radius: 8px;
                                z-index: 1000;
                                font-family: 'Inter', sans-serif;
                            ">
                        <!-- Header Info Pengguna -->
                        <div
                            style="padding: 1rem; border-bottom: 2px solid #000; display: flex; align-items: center; gap: 0.75rem;">
                            <img src="/uploads/users/<?= !empty($model['authProfile']['img']) ? htmlspecialchars($model['authProfile']['img']) : 'default.png' ?>"
                                alt="Profile"
                                style="width: 45px; height: 45px; border-radius: 50%; border: 2px solid #000; object-fit: cover;">
                            <div>
                                <div
                                    style="font-family: 'Space Grotesk', sans-serif; font-weight: 700; font-size: 1rem;">
                                    <?= htmlspecialchars($model['authProfile']['name'] ?? 'Anggota') ?>
                                </div>
                                <a href="/profile" style="font-size: 0.8rem; color: #666; text-decoration: none;">Lihat
                                    profil</a>
                            </div>
                        </div>

                        <!-- List Link Menu -->
                        <ul style="list-style: none; padding: 0.5rem 0; margin: 0;">
                            <li>
                                <a href="/me/article"
                                    style="display: flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1rem; text-decoration: none; color: #000; font-weight: 500; font-size: 0.9rem;">
                                    Artikel saya
                                </a>
                            </li>
                            <li>
                                <a href="/account"
                                    style="display: flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1rem; text-decoration: none; color: #000; font-weight: 500; font-size: 0.9rem;">
                                    Pengaturan Akun
                                </a>
                            </li>
                            <li style="border-top: 2px solid #000; margin-top: 0.5rem; padding-top: 0.5rem;">
                                <a href="/article"
                                     style="display: flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1rem; text-decoration: none; color: #000; font-weight: 500; font-size: 0.9rem;">
                                    Artikel
                                </a>
                            </li>
                            <li style="border-top: 2px solid #000; margin-top: 0.5rem; padding-top: 0.5rem;">
                                <a href="/logout"
                                    style="display: flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1rem; text-decoration: none; color: #ff5757; font-weight: 700; font-size: 0.9rem;">
                                    Logout
                                </a>
                            </li>
                        </ul>
                    </div>

                </div>

            </div>
        </div>
    </nav>