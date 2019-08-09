<?php $this->setLayoutVar('title', 'ホーム');?>

<h2>ホーム</h2>
<p><a href="/logout">ログアウト</a></p>

<?php
if (!empty(Message::get())) {
    foreach (Message::get() as $key => $msg) {
        echo ("<li>");
        $this->escapeEcho($msg->getStr());
        echo ("</li>");
    }
}
?>

<h3>なまえ</h3>
<p><?php $this->escapeEcho(Auth::user()->name);?></p>

<form action="/post/create" method="post" enctype="multipart/form-data">
    <input type="hidden" name="_token" value="<?=CsrfToken::publication()?>">
    <input type="text" name="text" id="text" placeholder="投稿用のテキストを140文字以内で書いてください" value="">
    <input type="file" id="image" name="image">
    <input type="submit" value="投稿">
</form>


<?php
echo ('<table>');
echo ('<tr><th>投稿</th><th>画像</th><th>なまえ</th><th>日時</th></tr>');
foreach ($posts as $post => $value) {

    echo '<tr>';
    echo '<td>';
    $this->escapeEcho($value["text"]);
    echo '</td>';
    echo '<td>';

    if (!empty($value["image"])) {
        echo '<img src="/upload/' . $value["image"] . '" alt="NoImage" width="100px" height="100px" >';
    }
    echo '</td>';
    echo '<td>';
    $this->escapeEcho($value["name"]);
    echo '</td>';
    echo '<td>';
    $this->escapeEcho($value["created_at"]);
    echo '</td>';
    echo '<td><a href="/post/' . $value["post_id"] . '">詳細表示';
    echo '</a></td>';
    echo '<tr>';
}
echo '</table>';

?>