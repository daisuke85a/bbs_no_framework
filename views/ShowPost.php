<h2>投稿表示画面</h2>

<form action="/post/<?= $postId ?>/delete" method="POST">
    <input type="submit" value="投稿削除">
</form>

<?php echo($_SERVER['REQUEST_URI']); ?>
