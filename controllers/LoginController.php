<?php

class LoginController extends Controller
{
    public function loginAction(){
        //TODO: ログイン処理

        return $this->render(
            [ 'login' => 'TRUE',
              'body' => 'default body'
            ],
            'Home.php'
        );
    }

    public function logoutAction(){
        //TODO: ログアウト処理

        return $this->render(
            [ 'login' => 'TRUE',
              'body' => 'default body'
            ],
            'Login.php'
        );
    }
    public function registerAction(){
        // TODO: ユーザー登録処理

        // TODO: バリデーションする
        $params = [
            "name" => $_POST['name'],
            "password" => $_POST['password'],
            "email" => $_POST['email']
        ];

        $user = new User();
        $user->insert($_POST['name'],$_POST['password'],$_POST['email']);

        return $this->render(
            [ 'login' => 'TRUE',
              'body' => 'default body'
            ],
            'Home.php'
        );
    }

    public function signupViewAction(){
        //TODO: サインアップ画面表示処理

        return $this->render(
            [ 'login' => 'TRUE',
              'body' => 'default body'
            ],
            'Signup.php'
        );
    }
}