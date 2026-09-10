<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <div class="hero-content">

            <?php if (isset($model['error'])) { ?>
                <div class="font-semibold neo-box login-card bg-primary mb-lg">
                    <?= $model['error'] ?>
                </div>
            <?php } ?>

            <div class="neo-card login-card">

                <div class="text-center mb-lg">
                    <h3>Log <span class="highlight highlight-yellow">in</span></h3>
                    <p class="mt-sm">
                        Silakan masuk untuk melanjutkan.
                    </p>
                </div>

                <form action="/login" method="post">

                    <input type="hidden" name="redirect" value="<?= htmlspecialchars($_GET['redirect'] ?? '') ?>">

                    <div class="mb-md">
                        <label for="email" class="font-semibold block mb-sm">
                            Email
                        </label>

                        <input type="email" name="email" id="email" class="login-input" placeholder="Masukkan email"
                            required>
                    </div>

                    <div class="mb-lg">
                        <label for="password" class="font-semibold block mb-sm">
                            Password
                        </label>

                        <div class="password-wrapper">
                            <input type="password" name="password" id="password" class="login-input password-input"
                                placeholder="Masukkan password" required>

                            <button type="button" class="password-toggle" id="password-toggle"
                                aria-label="Tampilkan password">
                                <svg id="password-eye" xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path
                                        d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="neo-btn w-full">
                        Login
                    </button>

                </form>

            </div>
        </div>
</section>

<style>
    .password-wrapper {
        position: relative;
        width: 100%;
    }

    .password-input {
        width: 100%;
        padding-right: 48px;
    }

    .password-toggle {
        position: absolute;
        top: 50%;
        right: 12px;
        transform: translateY(-50%);

        display: flex;
        align-items: center;
        justify-content: center;

        border: none;
        background: transparent;
        padding: 4px;

        cursor: pointer;
        color: var(--dark);

        transition:
            transform 0.15s ease,
            opacity 0.15s ease;
    }

    .password-toggle:hover {
        opacity: 0.7;
    }

    .password-toggle:active {
        transform: translateY(-50%) scale(0.9);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        const passwordInput = document.getElementById('password');
        const passwordToggle = document.getElementById('password-toggle');
        const passwordEye = document.getElementById('password-eye');

        passwordToggle.addEventListener('click', function () {

            const isPassword = passwordInput.type === 'password';

            passwordInput.type = isPassword ? 'text' : 'password';

            passwordEye.innerHTML = isPassword
                ? `
                <path d="M3 3l18 18"/>
                <path d="M10.584 10.587a2 2 0 0 0 2.829 2.828"/>
                <path d="M9.363 5.365A10.466 10.466 0 0 1 12 5c5 0 8.5 4 10 7a13.16 13.16 0 0 1-2.147 3.314"/>
                <path d="M6.228 6.228C4.697 7.3 3.5 9 2 12c1.5 3 5 7 10 7a10.44 10.44 0 0 0 4.228-.878"/>
            `
                : `
                <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"/>
                <circle cx="12" cy="12" r="3"/>
            `;

            passwordToggle.setAttribute(
                'aria-label',
                isPassword ? 'Sembunyikan password' : 'Tampilkan password'
            );
        });

    });
</script>