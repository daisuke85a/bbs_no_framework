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

        //ログイン中
        if (Auth::check()) {
            //TODO: バリデーション処理を追加

            $post = new Post();
            $post->insert($_POST["text"]);
        }
        return $this->redirect('/');

    }

    public function deleteAction($params)
    {
        //ログイン中
        if (Auth::check()) {
            var_dump($params['id']);

            $post = new Post();
            $post = $post->fetch($params['id']);

            //自分のツイートの場合は削除できる
            var_dump("post user_id");
            var_dump($post["user_id"]);
            var_dump("auth user_id");

            var_dump(Auth::user()->id);

            if ($post["user_id"] === Auth::user()->id) {
                $post = new Post();
                $post->delete($params['id']);
            } else {
                var_dump("otehr user tweet");
            }
            //詳細画面を表示する
        }
    }
}