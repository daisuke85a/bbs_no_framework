<h2>サインアップ画面</h2>
<?php

if (!empty(\core\Message::get())) {
    foreach (\core\Message::get() as $key => $msg) {
        echo ("<li>");
        $this->escapeEcho($msg->getStr());
        echo ("</li>");
    }
}
?>

<form action="/register" method="POST">
    <input type="hidden" name="_token" value="<?=\core\CsrfToken::publication()?>">
    <input type="text" name="name" placeholder="名前を入力ください">
    <input type="email" name="email" placeholder="emailアドレスを入力ください">
    <input type="password" name="password" placeholder="パスワードを入力ください">
    <input type="submit" value="サインアップ">
</form>

<a href="/">ログイン画面へ</a>