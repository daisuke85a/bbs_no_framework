<?php var_dump($errors);?>
<h2>サインアップ画面</h2>
<form action="/register" method="POST">
    <input type="text" name="name" placeholder="名前を入力ください">
    <input type="email" name="email" placeholder="emailアドレスを入力ください">
    <input type="password" name="password" placeholder="パスワードを入力ください">
    <input type="submit" value="サインアップ">
</form>

<a href="/">ログイン画面へ</a>

<?php echo ($_SERVER['REQUEST_URI']); ?>