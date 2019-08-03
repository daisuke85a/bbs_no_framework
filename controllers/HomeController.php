<?php

class HomeController extends Controller
{
    public function welcomeAction()
    {
        //TODO: ログイン中ならホームを表示。未ログインならログイン画面を表示

        //ログイン中
        if (Auth::check()) {
            //全投稿を表示
            $post = new Post();
            $posts = $post->fetchAll();

            return $this->render(
                ['posts' => $posts],
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