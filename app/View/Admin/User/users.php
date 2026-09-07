<h2><?= $model['title'] ?></h2>

<a href="/register">Register New User</a><br><br>

<?php if (!isset($model['error'])) { ?>
    <?php foreach ($model['user'] as $user) { ?>
        <?php if ($user['role'] == 'admin') { ?>
            <p>
                <?= $user['id'] ?>
            </p>
            <p>
                <?= $user['name'] ?>
            </p>
            <p>Administrator</p><br>
            <hr>
        <?php } else { ?>
            <p><?= $user['id'] ?></p>
            <p><?= $user['name'] ?></p>
            <img src="<?= $user['img'] ?>" alt="img user" style="height: 50px; width: auto;">
        <?php } ?>

    <?php } ?>
<?php } else { ?>
    <p><?= $model['error'] ?></p>
<?php } ?>