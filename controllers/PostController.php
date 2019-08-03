<?php

class PostController extends Controller
{
    public function showAction($id)
    {
        //TODO: 投稿表示処理の追加

        return $this->render(
            ['login' => 'TRUE',
                'body' => 'default body',
                'postId' => $id,
            ],
            'ShowPost.php'
        );
    }

    public function createAction()
    {
        //TODO: バリデーション処理を追加

        $post = new Post();
        var_dump($_POST);
        $post->insert($_POST["text"]);

        return $this->redirect('/');

    }
}