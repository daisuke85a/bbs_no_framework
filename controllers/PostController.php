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
                // エラーメッセージ表示は無い（表示ボタンを非表示にし、通常はありえない操作になるため）
                return $this->redirect('/');
            }
        } else {
            return $this->render(
                [],
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

        // 未定義である・複数ファイルである・$_FILES Corruption 攻撃を受けた
        // どれかに該当していれば不正なパラメータとして処理する
        if (!isset($_FILES['image']['error']) || !is_int($_FILES['image']['error'])) {
            Message::set('image', '画像ファイルのパラメータが不正です');
            return false;
        }

        // $_FILES['image']['error'] の値を確認
        switch ($_FILES['image']['error']) {
            case UPLOAD_ERR_OK: // OK
                break;
            case UPLOAD_ERR_NO_FILE: // ファイル未選択
                // ファイル投稿なし(textのみ投稿)と判断
                return $validation;
            case UPLOAD_ERR_INI_SIZE: // php.ini定義の最大サイズ超過
            case UPLOAD_ERR_FORM_SIZE: // フォーム定義の最大サイズ超過 (設定した場合のみ)
                Message::set('image', 'ファイルサイズが大きすぎます!');
                return false;
            default:
                Message::set('image', 'その他エラーが発生しました');
                return false;
        }

        // ここで定義するサイズ上限のオーバーチェック
        // 2MB以下とする
        if ($_FILES['image']['size'] > 2000000) {
            Message::set('image', 'ファイルサイズが大きすぎます!');
            return false;
        }

        // $_FILES['image']['mime']の値はブラウザ側で偽装可能なので
        // MIMEタイプに対応する拡張子を自前で取得する
        if (!$ext = array_search(
            mime_content_type($_FILES['image']['tmp_name']),
            array(
                'gif' => 'image/gif',
                'jpg' => 'image/jpeg',
                'png' => 'image/png',
            ),
            true
        )) {
            Message::set('image', 'ファイル形式が不正です。git/jpg/pngのいずれかを指定してください');
            return false;
        }

        // TODO: https: //qiita.com/mpyw/items/939964377766a54d4682 の内容をチェックする
        // TODO: https: //docs.google.com/spreadsheets/d/1GnjS4lJvU8j3fE7tRANCsSm6FgQJ0ytDlTQeIF75h_E/edit#gid=0 の内容をチェックする

        // 画像ファイルの中身が不正じゃないか？ TODO::どうやってチェックする？
        var_dump($validation);

        return $validation;
    }

    public function createAction()
    {

        //ログイン中
        if (Auth::check()) {
            //TODO: バリデーション処理を追加
            if ($this->validateCreateAction()) {
                $post = new Post();
                $post->insert($_POST["text"]);
            }
        }
        if (empty($_POST["reply_id"])) {
            return $this->redirect('/');
        } else {
            return $this->redirect('/post/' . $_POST["reply_id"]);
        }
    }

    public function deleteAction($params)
    {
        //ログイン中
        if (Auth::check()) {

            $post = new Post();
            $post = $post->fetch($params['id']);

            //自分のツイートの場合は削除できる
            if ($post["user_id"] === Auth::user()->id) {
                $post = new Post();
                $post->delete($params['id']);
            } else {
                Message::set('delete', '自分のツイート以外は削除できません。');
            }

            //ホーム画面を表示する
            return $this->redirect('/');
        }
    }
}