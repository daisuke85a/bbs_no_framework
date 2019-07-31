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
}