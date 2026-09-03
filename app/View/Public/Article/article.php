<h2><?= $model['title'] ?></h2>

<a href="/article/add">Add</a><br><br>

<?php if (isset($model['article'])) { ?>
    <?php foreach ($model['article'] as $article) { ?>
        <div class="mainArticle">
            <p><span>Owner:</span> <?= $article['name'] ?></p>
            <p><span>Title :</span><?= $article['title'] ?></p>
            <p><span>Content: </span><?= $article['content'] ?></p>
        </div>
        <br>
    <?php } ?>
<?php } else { ?>

    <div>
        <br>
        <p><?= $model['emptyArticle'] ?></p>
    </div>
<?php } ?>