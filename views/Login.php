<h2>ログイン画面</h2>

<?php
if (!empty(Message::get())) {
    foreach (Message::get() as $key => $msg) {
        echo ("<li>");
        $this->escapeEcho($msg->getStr());
        echo ("</li>");
    }
}
?>

<form action="/login" method="POST">
    <input type="hidden" name="_token" value="<?=CsrfToken::publication()?>">
    <input type="email" name="email" placeholder="emailアドレスを入力ください">
    <input type="password" name="password" placeholder="パスワードを入力ください">
    <input type="submit" value="ログイン">
</form>

<a href="/signup">サインアップ画面へ</a>