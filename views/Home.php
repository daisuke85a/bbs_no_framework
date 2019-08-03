<h2>ホーム</h2>
<p><a href="/logout">ログアウト</a></p>
<h2>なまえ</h2>
<p><?php $this->escapeEcho(Auth::user()->name);?></p>

<form action="/post/create" method="post">
    <input type="text" name="text" id="text" placeholder="投稿用のテキストを140文字以内で書いてください" value="">
    <input type="submit" value="投稿">
</form>