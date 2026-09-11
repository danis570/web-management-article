<?php

$user = $model['user'] ?? null;
$error = $model['error'] ?? null;

?>

<style>
    /* ========================================================
       ACCOUNT SETTINGS PAGE
    ======================================================== */

    .account-settings-page {
        width: 100%;
        box-sizing: border-box;
    }


    /* ========================================================
       HEADER
    ======================================================== */

    .account-settings-header {
        width: 100%;
        text-align: center;
        margin: 0 auto 3rem auto;
        box-sizing: border-box;
    }

    .account-settings-header h1 {
        margin: 0 0 8px 0;
        text-align: center;
    }

    .account-settings-header p {
        margin: 0;
        text-align: center;
    }


    /* ========================================================
       NOTIFICATION
    ======================================================== */

    .account-settings-message {
        width: 100%;
        box-sizing: border-box;
        margin-bottom: 1.5rem;
        padding: 15px;
    }


    /* ========================================================
       SETTINGS GRID
    ======================================================== */

    .account-settings-grid {
        width: 100%;
        max-width: 1000px;
        margin: 0 auto;

        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));

        gap: 24px;

        box-sizing: border-box;
    }

    .account-settings-header,
    .account-settings-grid {
        width: 100%;
        max-width: 1000px;
        margin-left: auto;
        margin-right: auto;
    }


    /* ========================================================
       SETTINGS CARD
    ======================================================== */

    .account-settings-card {
        width: 100%;
        box-sizing: border-box;
    }

    .account-settings-card-header {
        margin-bottom: 2rem;
    }

    .account-settings-card-header h2 {
        margin: 0 0 6px 0;
    }

    .account-settings-card-header p {
        margin: 0;
        font-size: 0.9rem;
    }


    /* ========================================================
       FORM
    ======================================================== */

    .account-settings-form {
        width: 100%;
    }

    .account-settings-form-group {
        width: 100%;
        margin-bottom: 1.5rem;
    }

    .account-settings-form-group label {
        display: block;
        margin-bottom: 0.5rem;
    }

    .account-settings-form-group input {
        width: 100%;
        box-sizing: border-box;
    }


    /* ========================================================
       BUTTON
    ======================================================== */

    .account-settings-button {
        width: 100%;
        box-sizing: border-box;
    }


    /* ========================================================
       HILANGKAN DEKORASI KUNING KHUSUS HALAMAN INI
       ======================================================== */

    .account-settings-page::before,
    .account-settings-page::after {
        display: none !important;
        content: none !important;
    }


    /* ========================================================
       MOBILE
    ======================================================== */

    @media (max-width: 768px) {

        .account-settings-header {
            margin-bottom: 2rem;
        }

        .account-settings-header h1 {
            font-size: 2rem;
        }

        .account-settings-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }

    }


    /* ========================================================
       SMALL MOBILE
    ======================================================== */

    @media (max-width: 480px) {

        .account-settings-header h1 {
            font-size: 1.7rem;
        }

        .account-settings-header p {
            font-size: 0.9rem;
        }

        .account-settings-grid {
            gap: 16px;
        }

    }
</style>


<!-- ========================================================
     ACCOUNT SETTINGS
======================================================== -->

<section class="hero account-settings-page">

    <div class="container">

        <div class="hero-content">


            <!-- ==================================================
                 HEADER
            ================================================== -->

            <div class="account-settings-header">

                <h1 class="text-3xl font-bold">
                    Account Settings
                </h1>

                <p class="text-gray-600 font-semibold">
                    Kelola email dan password akun kamu.
                </p>

            </div>


            <!-- ==================================================
                 ERROR NOTIFICATION
            ================================================== -->

            <?php if ($error !== null) { ?>

                <div class="
                    font-semibold
                    neo-box
                    login-card
                    bg-red-400
                    account-settings-message
                ">

                    <?= htmlspecialchars($error) ?>

                </div>

            <?php } ?>


            <!-- ==================================================
                 FLASH MESSAGE
            ================================================== -->

            <?php if (isset($_SESSION['flash_message'])) { ?>

                <div class="
                    flex
                    items-center
                    justify-between
                    font-semibold
                    neo-box
                    login-card
                    bg-primary
                    account-settings-message
                ">

                    <span>
                        <?= htmlspecialchars(
                            $_SESSION['flash_message']
                        ) ?>
                    </span>


                    <a href="/" class="neo-btn">
                        Kembali
                    </a>

                </div>

                <?php unset($_SESSION['flash_message']); ?>

            <?php } ?>


            <!-- ==================================================
                 SETTINGS GRID
            ================================================== -->

            <div class="account-settings-grid">


                <!-- ==================================================
                     CHANGE EMAIL
                ================================================== -->

                <div class="
                    neo-card
                    login-card
                    account-settings-card
                ">


                    <!-- CARD HEADER -->

                    <div class="account-settings-card-header">

                        <h2 class="text-2xl font-bold">
                            Ubah Email
                        </h2>

                        <p class="text-gray-600 font-semibold">
                            Gunakan email yang masih aktif.
                        </p>

                    </div>


                    <!-- FORM -->

                    <form action="/account/email" method="POST" class="account-settings-form">


                        <!-- EMAIL -->

                        <div class="account-settings-form-group">

                            <label for="email" class="font-semibold">
                                Email Baru
                            </label>


                            <input type="email" id="email" name="email" value="<?= htmlspecialchars(
                                $user->email ?? ''
                            ) ?>" class="login-input w-full" placeholder="Masukkan email baru" required>

                        </div>


                        <!-- BUTTON -->

                        <button type="submit" class="neo-btn account-settings-button">
                            Ubah Email
                        </button>

                    </form>

                </div>


                <!-- ==================================================
                     CHANGE PASSWORD
                ================================================== -->

                <div class="
                    neo-card
                    login-card
                    account-settings-card
                ">


                    <!-- CARD HEADER -->

                    <div class="account-settings-card-header">

                        <h2 class="text-2xl font-bold">
                            Ubah Password
                        </h2>

                        <p class="text-gray-600 font-semibold">
                            Gunakan password baru minimal 8 karakter.
                        </p>

                    </div>


                    <!-- FORM -->

                    <form action="/account/password" method="POST" class="account-settings-form">


                        <!-- CURRENT PASSWORD -->

                        <div class="account-settings-form-group">

                            <label for="current_password" class="font-semibold">
                                Password Saat Ini
                            </label>


                            <input type="password" id="current_password" name="current_password"
                                class="login-input w-full" placeholder="Masukkan password saat ini" required>

                        </div>


                        <!-- NEW PASSWORD -->

                        <div class="account-settings-form-group">

                            <label for="new_password" class="font-semibold">
                                Password Baru
                            </label>


                            <input type="password" id="new_password" name="new_password" class="login-input w-full"
                                placeholder="Minimal 8 karakter" minlength="8" required>

                        </div>


                        <!-- BUTTON -->

                        <button type="submit" class="neo-btn account-settings-button">
                            Ubah Password
                        </button>

                    </form>

                </div>


            </div>

        </div>

    </div>

</section>