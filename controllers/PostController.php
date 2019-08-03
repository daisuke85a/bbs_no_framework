<?php

class PostController extends Controller
{
    public function showAction($params)
    {
        //ログイン中
        if (Auth::check()) {
            //全投稿を表示
            $post = new Post();
            $replyPosts = $post->fetchReplay($params['id']);
            $post = $post->fetch($params['id']);

            return $this->render(
                ['posts' => $replyPosts,
                    'post' => $post,
                    'postId' => $params['id'],
                ],
                'ShowPost.php'
            );
        } else {
            return $this->render(
                ['login' => 'TRUE',
                ],
                'Login.php'
            );
        }

        //TODO: 投稿表示処理の追加

    }

    public function createAction()
    {
        //TODO: バリデーション処理を追加

        $post = new Post();
        $post->insert($_POST["text"]);

        return $this->redirect('/');

    }

    public function deleteAction($id)
    {

        var_dump($id['id']);

    }
}