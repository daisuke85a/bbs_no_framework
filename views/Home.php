<h2>ホーム</h2>
<p><a href="/logout">ログアウト</a></p>
<h3>なまえ</h3>
<p><?php $this->escapeEcho(Auth::user()->name);?></p>

<form action="/post/create" method="post">
    <input type="text" name="text" id="text" placeholder="投稿用のテキストを140文字以内で書いてください" value="">
    <input type="submit" value="投稿">
</form>

<?php
echo ('<table>');
echo ('<tr><th>投稿</th><th>なまえ</th><th>日時</th></tr>');
foreach ($posts as $post => $value) {
    echo ('<tr>');
    echo ('<td>');
    $this->escapeEcho($value["text"]);
    echo ('</td>');
    echo ('<td>');
    $this->escapeEcho($value["name"]);
    echo ('</td>');
    echo ('<td>');
    $this->escapeEcho($value["created_at"]);
    echo ('</td>');
    echo ('<tr>');
}
echo ('</table>');

?>