<?php

class LoginController extends Controller
{

    private $errors = [];

    public function login(string $email, string $password)
    {
        //ログイン処理
        if (Auth::authenticate($email, $password)) {
            //全投稿を表示
            $post = new Post();
            $posts = $post->fetchAll();

            return $this->redirect("/");

            // return $this->render(
            //     ['posts' => $posts],
            //     'Home.php'
            // );

        } else {
            //ログイン失敗したらエラーメッセージとともにLogin画面を再表示

            return $this->render(
                ["errors" => Auth::$errors],
                'Login.php'
            );

            // return $this->render(
            //     ["errors", Auth::$errors],
            //     'Login.php'
            // );

        }

    }

    private function validateLogin(): bool
    {

        $validation = true;

        if (empty($_POST['password'])) {
            Message::set('password', 'パスワードが未入力です。入力をお願いします');
            $validation = false;
            // $this->errors['password'] = 'パスワードが未入力です。入力をお願いします';
        }

        if (empty($_POST['email'])) {
            Message::set('email', 'メールアドレスが未入力です。入力をお願いします');
            $validation = false;
            // $this->errors['email'] = 'メールアドレスが未入力です。入力をお願いします';
        }

        return $validation;

    }

    public function loginAction()
    {
        //TODO: ログイン処理
        if ($this->validateLogin()) {
            return $this->login($_POST['email'], $_POST['password']);
        } else {
            return $this->redirect('/');
        }
        //入力バリデーションは未入力チェックだけする
        //文字数制限チェックはしない。バレるといけないから。

    }

    public function logoutAction()
    {
        //TODO: ログアウト処理

        Auth::releaseAuthenticate();

        return $this->render(
            ["errors", []],
            'Login.php'
        );
    }

    private function validateRegister(): bool
    {

        $validation = true;
        $this->errors = [];

        if (empty($_POST['name'])) {
            Message::set('name', 'お名前が未入力です。入力をお願いします');
            $validation = false;
            // $this->errors['name'] = 'お名前が未入力です。入力をお願いします';
        }

        if (empty($_POST['password'])) {
            Message::set('password', 'パスワードが未入力です。入力をお願いします');
            $validation = false;
            // $this->errors['password'] = 'パスワードが未入力です。入力をお願いします';
        }

        if (empty($_POST['email'])) {
            Message::set('email', 'メールアドレスが未入力です。入力をお願いします');
            $validation = false;

            // $this->errors['email'] = 'メールアドレスが未入力です。入力をお願いします';
        }

        //nameのバリデーション
        //30文字以上の場合はNG
        if (mb_strlen($_POST['name'], mb_internal_encoding()) > 30) {
            Message::set('name', '名前は30文字未満にしてください。');
            $validation = false;

            // $this->errors["name"] = "名前は30文字未満にしてください。";
        }

        //emailのバリデーション
        //30文字以上の場合はNG
        if (mb_strlen($_POST['email'], mb_internal_encoding()) > 30) {
            Message::set('email', 'メールアドレスは30文字未満にしてください。');
            $validation = false;

            // $this->errors["email"] = "メールアドレスは30文字未満にしてください。";
        }

        //passwordのバリデーション
        //30文字以上の場合はNG
        if (mb_strlen($_POST['password'], mb_internal_encoding()) > 30) {
            Message::set('password', 'メールアドレスは30文字未満にしてください。');
            $validation = false;

            // $this->errors["password"] = "パスワードは30文字未満にしてください。";
        }

        return $validation;

    }

    public function registerAction()
    {
        // TODO: ユーザー登録処理

        if ($this->validateRegister()) {

            $user = new User();
            $user->insert($_POST['name'], $_POST['password'], $_POST['email']);

            return $this->login($_POST['email'], $_POST['password']);

            // return $this->render(
            //     ['errors' => $this->errors],
            //     'Home.php'
            // );
        }

        //TODO: エラー発生時にアドレスバーがregisterになってしまうのがかっこ悪い。
        //本当はsignupにしたいけど、以下コードを入れるとエラーメッセージが表示できない。
        //$this->redirect('/signup');

        return $this->render(
            ['errors' => $this->errors],
            'Signup.php'
        );

    }

    public function signupViewAction()
    {
        //TODO: サインアップ画面表示処理

        return $this->render(
            ['errors' => 'TRUE',
                'body' => 'default body',
            ],
            'Signup.php'
        );
    }
}