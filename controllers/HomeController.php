<?php

class HomeController extends Controller
{
    public function welcomeAction()
    {
        //TODO: ログイン中ならホームを表示。未ログインならログイン画面を表示

        if (Auth::check()) {
            return $this->render(
                [],
                'Home.php'
            );
        } else {
            return $this->render(
                ['login' => 'TRUE',
                ],
                'Login.php'
            );
        }
    }
}
