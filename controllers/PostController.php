<?php

class PostController extends Controller
{
    public function showAction($id)
    {
        //TODO: Post内容の取得

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
        //TODO: Post内容をDBへ投稿

        $post = new Post();
        var_dump($_POST);
        $post->insert($_POST["text"]);

        return $this->redirect('/');

    }
}