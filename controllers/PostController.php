<?php

class PostController extends Controller
{
    public function showAction($params)
    {
        //ログイン中
        if (Auth::check()) {
            //全投稿を表示
            $post = new Post();
            $post = $post->fetch($params['id']);

            if ($post['valid'] == 1) {
                $replyPosts = new Post();
                $replyPosts = $replyPosts->fetchReplay($params['id']);

                return $this->render(
                    ['posts' => $replyPosts,
                        'post' => $post,
                        'postId' => $params['id'],
                    ],
                    'ShowPost.php'
                );
            } else {
                // 削除済みのツイートです みたいな表示にするのも有りかも
                return $this->redirect('/');
            }
        } else {
            return $this->render(
                ['login' => 'TRUE',
                ],
                'Login.php'
            );
        }

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
            if ($post["user_id"] === Auth::user()->id) {
                $post = new Post();
                $post->delete($params['id']);
            }

            //ホーム画面を表示する
            return $this->redirect('/');
        }
    }
}