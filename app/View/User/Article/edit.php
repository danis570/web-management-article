<div>
    <?php if (isset($model['error'])) { ?>
        <?= $model['error'] ?>
    <?php } ?>
</div>

<h2><?= $model['title'] ?></h2>

<?php if (!isset($model['error'])) { ?>
    <form action="/article/edit" method="post">
        <label for="title">
            Title:
            <input type="text" value="<?= $model['article']['title'] ?? '' ?>" name="title" id="title">
        </label>
        <label for="content">
            Content:
            <textarea name="content" id="content" rows="10" cols="50"><?= $model['article']['content'] ?? '' ?></textarea>
        </label>
        <button value="<?= $model['article']['id'] ?? '' ?>" name="id" type="submit">Simpan</button>
    </form>
<?php } ?>