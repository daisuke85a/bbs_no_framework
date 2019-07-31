<h2>ホーム</h2>
<a href="/logout">ログアウト</a>

<form action="/post/create" method="POST">
    <input type="text" placeholder="投稿用のテキストを140文字以内で書いてください" value="">
    <input type="submit" value="投稿">
</form>

<p>login= <?php echo($login); ?> </p>
<p>body= <?php echo($body); ?> </p>
<?php echo($_SERVER['REQUEST_URI']); ?>
