<h2><?= $model['title'] ?></h2>

<a href="/article/add">Add</a><br><br>

<?php if (isset($model['article'])) { ?>
    <?php foreach ($model['article'] as $article) { ?>
        <div>
            <p><span>Article ID :</span> <?= $article['id'] ?></p>
            <p><span>Article User ID:</span> <?= $article['user_id'] ?></p>
            <p><span>Article Title :</span><?= $article['title'] ?></p>
            <p><span>Article Content: </span><?= $article['content'] ?></p>
        </div>
        <br>
    <?php } ?>
<?php } else { ?>

    <div>
        <br>`
        <p><?= $model['emptyArticle'] ?></p>
    </div>
<?php } ?>