<h2><?= $model['title'] ?></h2>

<a href="/article/add">Add</a><br><br>

<?php if (isset($model['article'])) { ?>
    <?php foreach ($model['article'] as $article) { ?>
        <div class="mainArticle">
            <p><span>Article ID :</span> <?= $article['id'] ?></p>
            <p><span>Article User ID:</span> <?= $article['user_id'] ?></p>
            <p><span>Article Title :</span><?= $article['title'] ?></p>
            <p><span>Article Content: </span><?= $article['content'] ?></p>
            <form action="/article/delete" method="post">
                <button class="deleteButton" name="id" value="<?= $article['id'] ?>" type="submit">Delete</button>
            </form>
            <form action="/article/edit" method="get">
                <button class="deleteButton" name="id" value="<?= $article['id'] ?>" type="submit">Edit</button>
            </form>
        </div>
        <br>
    <?php } ?>
<?php } else { ?>

    <div>
        <br>
        <p><?= $model['emptyArticle'] ?></p>
    </div>
<?php } ?>