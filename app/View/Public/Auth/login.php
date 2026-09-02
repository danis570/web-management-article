<?php if (isset($model['error'])) { ?>
    <div>
        <p>
            <?= $model['error'] ?>
        </p>
    </div>
<?php } ?>

<h2><?= $model['title'] ?></h2>

<form action="/login" method="post">
    <label for="email">Email
        <input type="text" name="email" id="email">
    </label>
    <label for="password">Password
        <input type="password" name="password" id="password">
    </label>
    <button type="submit">Login</button>
</form>