<?php
class Post
{
    public function insert($text, $reply_id = null, $image = null)
    {

        var_dump($_FILES['image']['tmp_name']);
        var_dump($_FILES['image']['name']);
        var_dump(basename($_FILES['image']['name']));

        $uploadDir = $_SERVER["DOCUMENT_ROOT"] . '/upload/';
        $uploadFileBaseName = basename($_FILES['image']['name']);
        $uploadFile = $uploadDir . $uploadFileBaseName;
        $uploadSuccess = false;

        // move_uploaded_fileについて
        // この関数は、filename で指定されたファイルが (PHP の HTTP POST アップロード機構によりアップロードされたという意味で)
        // 有効なアップロードファイルであるかどうかを確認します。
        // そのファイルが有効な場合、destination で指定したファイル名に移動されます。
        // TODO:ファイル名をUNIQUEな文字列に変更する。同名ファイルがアップされたときの上書きを防ぐ
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
            ':reply_id' => $reply_id,
            ':image' => $image,
            ':valid' => true,
        ];

        $stmt->execute($params);

        return;
    }

    public function fetchAll(): array
    {
        //emailに該当するuserを抽出する(emailはUNIQUE)
        $stmt = DB::$connect->prepare(
            'SELECT * FROM posts INNER JOIN users
        ON posts.user_id = users.id;'
        );

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }

}