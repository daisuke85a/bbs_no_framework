<?php
class Post
{
    public function insert($text, $reply_id = null, $image = null)
    {
        $uploadDir = $_SERVER["DOCUMENT_ROOT"] . '/upload/';
        $uploadFileBaseName = basename($_FILES['image']['name']);
        $uploadFile = $uploadDir . $uploadFileBaseName;
        $uploadSuccess = false;

        // move_uploaded_fileについて
        // この関数は、filename で指定されたファイルが (PHP の HTTP POST アップロード機構によりアップロードされたという意味で)
        // 有効なアップロードファイルであるかどうかを確認します。
        // そのファイルが有効な場合、destination で指定したファイル名に移動されます。
        // TODO:ファイル名をUNIQUEな文字列に変更する。同名ファイルがアップされたときの上書きを防ぐ

        // PHPによって格納された一時ファイル保存先($_FILES['image']['tmp_name'])から、本ファイル保存先に移動する。

        if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
            $uploadSuccess = true;
            $image = $uploadFileBaseName;
        } else {
            $uploadSuccess = false;
            $image = null;
        }

        $stmt = DB::$connect->prepare(
            'INSERT INTO posts (text , user_id, reply_id , image, valid ) VALUES(:text , :user_id, :reply_id, :image, :valid)'
        );

        $params =
            [':text' => $text,
            ':user_id' => Auth::user()->id,
            ':reply_id' => $_POST["reply_id"],
            ':image' => $image,
            ':valid' => true,
        ];

        $stmt->execute($params);

        return;
    }

    public function fetchAll(): array
    {
        //全ての投稿を取得する
        //TODO: 投稿が増えると処理やメモリに時間がかかる。本来は細切れに取得するのが望ましい。
        $stmt = DB::$connect->prepare(
            'SELECT *, posts.id AS post_id FROM posts INNER JOIN users
        ON posts.user_id = users.id;'
        );

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }

    public function fetch($postId)
    {
        //emailに該当するuserを抽出する(emailはUNIQUE)
        $stmt = DB::$connect->prepare(
            'SELECT *, posts.id AS post_id FROM posts INNER JOIN users
        ON posts.user_id = users.id WHERE posts.id = :postId'
        );

        $params =
            [
            ':postId' => $postId,
        ];

        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC);

    }

    public function fetchReplay($postId): array
    {
        //ある投稿のリプライを全て取得する
        //TODO: 投稿が増えると処理やメモリに時間がかかる。本来は細切れに取得するのが望ましい。
        $stmt = DB::$connect->prepare(
            'SELECT *, posts.id AS post_id FROM posts INNER JOIN users
                ON posts.user_id = users.id WHERE posts.reply_id = :postId'
        );

        $params =
            [':postId' => $postId];

        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }

    public function delete($id): bool
    {
        var_dump("delete");
        var_dump($id);

        $stmt = DB::$connect->prepare(
            'UPDATE posts SET valid = 0 WHERE id = :id'
        );

        $params =
            [':id' => $id];

        $stmt->execute($params);
        return true;
    }

}
