<?php

class HomeController extends Controller
{
    public function welcomeAction()
    {

        //ログイン中
        if (Auth::check()) {
            //1ページ目を表示
            $post = new Post();
            $posts = $post->fetchPage(1);

            return $this->render(
                ['posts' => $posts,
                    'page' => 1,
                ],
                'Home.php'
            );
        } else {
            return $this->render(
                [],
                'Login.php'
            );
        }
    }
}