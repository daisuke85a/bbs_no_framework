<?php

namespace models;

use Core\Auth;
use Core\DB;
use Core\Message;

class Post
{
    //1ページあたりのポスト数
    public $postPerPage = 10;

    public function getPagesNumber(): int
    {
        // 有効なポスト数の合計を求める
        $stmt = DB::$connect->prepare(
            'SELECT COUNT(*) FROM posts WHERE posts.valid = 1'
        );
        $stmt->execute();
        $postNum = (int) $stmt->fetchColumn();

        // 1ページあたりのポスト数で割る
        return ((int) ($postNum / $this->postPerPage)) + 1;

    }

    private function moveImageFile(): string
    {
        $uploadDir = $_SERVER["DOCUMENT_ROOT"] . '/upload/';
        $uploadFileBaseName = basename($_FILES['image']['name']);
        $uploadSuccess = false;

        $ext = array_search(
            mime_content_type($_FILES['image']['tmp_name']),
            array(
                'gif' => 'image/gif',
                'jpg' => 'image/jpeg',
                'png' => 'image/png',
            ),
            true
        );

        // ファイルデータからsha256ハッシュを取ってファイル名を決定し，保存する
        // ファイル名をUNIQUEな文字列に変更する。同名ファイルがアップされたときの上書きを防ぐ
        $uploadFileBaseName = hash_file("sha256", $_FILES['image']['tmp_name']) . $ext;
        $uploadFileBaseName = sprintf('%s.%s', $uploadFileBaseName, $ext);

        $uploadFile = $uploadDir . $uploadFileBaseName;

        // move_uploaded_fileについて
        // この関数は、filename で指定されたファイルが (PHP の HTTP POST アップロード機構によりアップロードされたという意味で)
        // 有効なアップロードファイルであるかどうかを確認します。
        // そのファイルが有効な場合、destination で指定したファイル名に移動されます。

        // PHPによって格納された一時ファイル保存先($_FILES['image']['tmp_name'])から、本ファイル保存先に移動する。
        if (move_uploaded_file(
            $_FILES['image']['tmp_name'],
            $uploadFile
        )) {
            $uploadSuccess = true;
            $image = $uploadFileBaseName;

        } else {
            $uploadSuccess = false;
            $image = null;
            Message::set('image', 'ファイル保存時にエラーが発生しました');
        }

        // ファイルのパーミッションを確実に0644に設定する
        // 自分は読み書き、他の人は読み込みのみ可能なファイル。
        // TODO::これを入れると画像表示できなくなったため削除。
        // chmod($uploadFile, 0644);

        return $uploadFileBaseName;

    }

    public function insert(string $text, int $reply_id = null, string $image = null): bool
    {

        if ($_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
            $image = $this->moveImageFile();
        }

        $stmt = DB::$connect->prepare(
            'INSERT INTO posts (text , user_id, reply_id , image, valid ) VALUES(:text , :user_id, :reply_id, :image, :valid)'
        );

        // bindValueで明示的に型付けする（暗黙的にSTRに変換されるのを防ぐ）
        $stmt->bindValue(':text', $text, \PDO::PARAM_STR);
        $stmt->bindValue(':image', $image, \PDO::PARAM_STR);
        $stmt->bindValue(':user_id', Auth::user()->id, \PDO::PARAM_INT);
        $stmt->bindValue(':reply_id', $_POST["reply_id"], \PDO::PARAM_INT);
        $stmt->bindValue(':valid', true, \PDO::PARAM_BOOL);

        $stmt->execute();

        return true;
    }

    public function fetchPage(int $page): array
    {

        //指定された投稿のページを取得する
        $stmt = DB::$connect->prepare(
            'SELECT *, posts.id AS post_id FROM posts INNER JOIN users ON posts.user_id = users.id WHERE posts.valid = 1 ORDER BY created_at DESC LIMIT :limitFirst , :limitEnd'
        );

        // 必ずbindValueで型を指定する。デフォルトではSTRとして扱われるため注意
        // 参考：http: //blog.a-way-out.net/blog/2013/12/15/pdo-prepare-statement-numeric-literal/
        // 参考：https: //blog.tokumaru.org/2009/09/implicit-type-conversion-of-SQL-is-trap-full.html#p01

        $stmt->bindValue(':limitFirst', (($page - 1) * $this->postPerPage), \PDO::PARAM_INT);
        $stmt->bindValue(':limitEnd', $this->postPerPage, \PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);

    }

    public function fetch(int $postId): array
    {
        //emailに該当するuserを抽出する(emailはUNIQUE)
        $stmt = DB::$connect->prepare(
            'SELECT *, posts.id AS post_id FROM posts INNER JOIN users
        ON posts.user_id = users.id WHERE posts.id = :postId AND posts.valid = 1'
        );

        $stmt->bindValue(':postId', $postId, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);

    }

    public function fetchReplay(int $postId): array
    {
        //ある投稿のリプライを全て取得する
        //TODO: 投稿が増えると処理やメモリに時間がかかる。本来は細切れに取得するのが望ましい。
        $stmt = DB::$connect->prepare(
            'SELECT *, posts.id AS post_id FROM posts INNER JOIN users
                ON posts.user_id = users.id WHERE posts.reply_id = :postId AND posts.valid = 1 ORDER BY created_at DESC'
        );

        $stmt->bindValue(':postId', $postId, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);

    }

    public function delete(int $id): bool
    {

        $stmt = DB::$connect->prepare(
            'UPDATE posts SET valid = 0 WHERE id = :id'
        );

        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();

        return true;
    }

}