<h2>ログイン画面</h2>
<form action="/login" method="POST">
    <input type="email" name="email" placeholder="emailアドレスを入力ください">
    <input type="password" name="password" placeholder="パスワードを入力ください">
    <input type="submit" value="ログイン">
</form>

<a href="/signup">サインアップ画面へ</a>

<?php echo($_SERVER['REQUEST_URI']); ?>
