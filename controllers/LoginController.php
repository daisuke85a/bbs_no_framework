<?php

class LoginController extends Controller
{
    public function loginAction()
    {
        //TODO: ログイン処理

        return $this->render(
            ['login' => 'TRUE',
                'body' => 'default body',
            ],
            'Home.php'
        );
    }

    public function logoutAction()
    {
        //TODO: ログアウト処理

        return $this->render(
            ['login' => 'TRUE',
                'body' => 'default body',
            ],
            'Login.php'
        );
    }
    public function registerAction()
    {
        // TODO: ユーザー登録処理

        // TODO: バリデーションする
        $params = [
            "name" => $_POST['name'],
            "password" => $_POST['password'],
            "email" => $_POST['email'],
        ];

        $validation = true;
        $errors = [];

        //全てが必須
        if (!empty($_POST['name']) &&
            !empty($_POST['password']) &&
            !empty($_POST['email'])) {

            //nameのバリデーション
            //30文字以上の場合はNG
            if (mb_strlen($_POST['name'], mb_internal_encoding()) > 30) {
                $validation = false;
            }

            //passwordのバリデーション
            //30文字以上の場合はNG
            if (mb_strlen($_POST['password'], mb_internal_encoding()) > 30) {
                $validation = false;
            }

            //emailのバリデーション
            // if(){
            //      $validation = false;
            // }
        }
        //未入力項目が残っている場合
        else {
            $errors["empty"] = "未入力項目があります。入力をお願いします";
            $validation = false;
        }

        if ($validation) {

            $user = new User();
            $user->insert($_POST['name'], $_POST['password'], $_POST['email']);

            return $this->render(
                ['errors' => $errors],
                'Home.php'
            );
        }

        //TODO: エラー発生時にアドレスバーがregisterになってしまうのがかっこ悪い。
        //本当はsignupにしたいけど、以下コードを入れるとエラーメッセージが表示できない。
        //$this->redirect('/signup');

        return $this->render(
            ['errors' => $errors],
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