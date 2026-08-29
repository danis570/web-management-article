<?php if (isset($model['error'])) { ?>
    <div>
        <p>
            <?= $model['error'] ?>
        </p>
    </div>
<?php } ?>

<h2><?= $model['title'] ?></h2>

<form action="/register" method="post">
    <label for="name">Name
        <input type="text" name="name" id="name">
    </label>
    <label for="email">Email
        <input type="text" name="email" id="email">
    </label>
    <label for="password">Password
        <input type="password" name="password" id="password">
    </label>
    <button type="submit">Register</button>
</form>