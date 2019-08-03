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

        return $stmt;

    }
}