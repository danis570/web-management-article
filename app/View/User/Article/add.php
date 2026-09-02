<div>
    <?php if (isset($model['error'])) { ?>
        <?= $model['error'] ?>
    <?php } ?>
</div>

<h2><?= $model['title'] ?></h2>

<form action="/article/add" method="post">
    <label for="title">
        Title:
        <input type="text" name="title" id="title">
    </label>
    <label for="content">
        Content:
        <textarea name="content" id="content" rows="10" cols="50"></textarea>
    </label>
    <button type="submit">Simpan</button>
</form>