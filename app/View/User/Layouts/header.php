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
            <ul class="menu">
                <?php if (($model['current'] ?? '') == 'article') { ?>
                    <li><a href="/article" class="active">Artikel</a></li>
                <?php } else { ?>
                    <li><a href="/article">Artikel</a></li>
                <?php } ?>

                <li><a href="/prifile">Profil</a></li>
                <li><a href="/logout">Logout</a></li>
            </ul>
            <button class="mobile-menu-button" id="menuBtn">☰</button>
        </div>
    </nav>