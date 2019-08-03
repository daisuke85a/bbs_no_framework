<?php
class Post
{
    public function insert($text, $reply_id = null, $image = null)
    {
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