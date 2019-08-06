<h2>投稿表示画面</h2>

<?php
if (!empty(Message::get())) {
    foreach (Message::get() as $key => $msg) {
        echo ("<li>");
        $this->escapeEcho($msg->getStr());
        echo ("</li>");
    }
}
?>

<h3>投稿表示</h3>

<?php

echo ('<table>');
echo ('<tr><th>投稿</th><th>画像</th><th>なまえ</th><th>日時</th></tr>');

echo '<tr>';
echo '<td>';
$this->escapeEcho($post["text"]);
echo '</td>';
echo '<td>';

if (!empty($post["image"])) {
    echo '<img src="/upload/' . $post["image"] . '" alt="NoImage" width="100px" height="100px" >';
}
echo '</td>';
echo '<td>';
$this->escapeEcho($post["name"]);
echo '</td>';
echo '<td>';
$this->escapeEcho($post["created_at"]);
echo '</td>';
echo '<td><a href="/post/' . $post["post_id"] . '">詳細表示';
echo '</a></td>';
echo '<tr>';

echo '</table>';
?>

<h3>リプライする</h3>
<form action="/post/create" method="post" enctype="multipart/form-data">
    <input type="hidden" name="_token" value="<?=CsrfToken::publication()?>">
    <input type="text" name="text" id="text" placeholder="投稿用のテキストを140文字以内で書いてください" value="">
    <input type="hidden" name="reply_id" value="<?=$postId?>" />
    <input type="file" id="image" name="image">
    <input type="submit" value="投稿">
</form>

<h3>削除する</h3>
<form action="/post/<?=$postId?>/delete" method="POST">
    <input type="hidden" name="_token" value="<?=CsrfToken::publication()?>">
    <input type="submit" value="投稿削除">
</form>

<h3>リプライ表示</h3>
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