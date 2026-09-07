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

                        <input type="password" name="password" id="password" class="login-input"
                            placeholder="Masukkan password" required>
                    </div>

                    <button type="submit" class="neo-btn w-full">
                        Login
                    </button>

                </form>

            </div>
        </div>
</section>