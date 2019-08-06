<?php

class LoginController extends Controller
{

    public function login(string $email, string $password)
    {
        //ログイン処理
        if (Auth::authenticate($email, $password)) {
            //全投稿を表示
            $post = new Post();
            $posts = $post->fetchAll();

            return $this->redirect("/");

        } else {
            //ログイン失敗したらエラーメッセージとともにLogin画面を再表示

            return $this->render(
                [],
                'Login.php'
            );
        }

    }

    private function validateLogin(): bool
    {
        //入力バリデーションは未入力チェックだけする
        //文字数制限チェックはしない。バレるといけないから。
        $validation = true;

        if (empty($_POST['password'])) {
            Message::set('password', 'パスワードが未入力です。入力をお願いします');
            $validation = false;
        }

        if (empty($_POST['email'])) {
            Message::set('email', 'メールアドレスが未入力です。入力をお願いします');
            $validation = false;
        }

        return $validation;

    }

    public function loginAction()
    {
        //ログイン処理
        if ($this->validateLogin()) {
            return $this->login($_POST['email'], $_POST['password']);
        } else {
            return $this->redirect('/');
        }
    }

    public function logoutAction()
    {
        //ログアウト処理

        Auth::releaseAuthenticate();

        return $this->render(
            [],
            'Login.php'
        );
    }

    private function validateRegister(): bool
    {

        $validation = true;

        if (empty($_POST['name'])) {
            Message::set('name', 'お名前が未入力です。入力をお願いします');
            $validation = false;
        }

        if (empty($_POST['password'])) {
            Message::set('password', 'パスワードが未入力です。入力をお願いします');
            $validation = false;
        }

        if (empty($_POST['email'])) {
            Message::set('email', 'メールアドレスが未入力です。入力をお願いします');
            $validation = false;
        }

        //nameのバリデーション
        //30文字以上の場合はNG
        if (mb_strlen($_POST['name'], mb_internal_encoding()) > 30) {
            Message::set('name', '名前は30文字未満にしてください。');
            $validation = false;
        }

        //emailのバリデーション
        //30文字以上の場合はNG
        if (mb_strlen($_POST['email'], mb_internal_encoding()) > 30) {
            Message::set('email', 'メールアドレスは30文字未満にしてください。');
            $validation = false;
        }

        //passwordのバリデーション
        //30文字以上の場合はNG
        if (mb_strlen($_POST['password'], mb_internal_encoding()) > 30) {
            Message::set('password', 'メールアドレスは30文字未満にしてください。');
            $validation = false;
        }

        return $validation;

    }

    public function registerAction()
    {
        //ユーザー登録処理

        if ($this->validateRegister()) {

            $user = new User();
            $user->insert($_POST['name'], $_POST['password'], $_POST['email']);

            return $this->login($_POST['email'], $_POST['password']);
        }

        $this->redirect('/signup');
    }

    public function signupViewAction()
    {
        //サインアップ画面表示処理

        return $this->render(
            [],
            'Signup.php'
        );
    }
}