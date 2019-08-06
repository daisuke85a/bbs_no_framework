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

    private function validateCreateAction(): bool
    {
        $validation = true;
        // ポスト文字列が空かどうか
        if (empty($_POST['text'])) {
            Message::set('text', '投稿内容が未入力です。入力をお願いします');
            $validation = false;
        }

        // ポスト文字列が140文字以内かどうか
        var_dump(mb_strlen($_POST['text'], mb_internal_encoding()));
        if (mb_strlen($_POST['text'], mb_internal_encoding()) > 140) {
            Message::set('text', '投稿文字は140文字以下にしてください。');
            $validation = false;
        }

        // TODO: 画像ファイルが対応する拡張子かどうか

        // 画像ファイルの中身が不正じゃないか？ TODO::どうやってチェックする？
        var_dump($validation);

        return $validation;
    }

    public function createAction()
    {

        var_dump(mb_strlen($_POST['text']));
        var_dump(mb_strlen($_POST['text'], mb_internal_encoding()));

        //ログイン中
        if (Auth::check()) {
            //TODO: バリデーション処理を追加
            if ($this->validateCreateAction()) {
                $post = new Post();
                $post->insert($_POST["text"]);
            }
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