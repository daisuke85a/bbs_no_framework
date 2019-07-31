<?php

class HomeController extends Controller
{
    public function welcomeAction(){
        //TODO: ログイン中ならホームを表示。未ログインならログイン画面を表示

        return $this->render(
            [ 'login' => 'TRUE',
            ],
            'Login.php'
        );
    }
}