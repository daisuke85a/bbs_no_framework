<?php

use Core\Auth;
use Core\Controller;

class HomeController extends Controller
{
    public function welcomeAction(): string
    {
        //ログイン中
        if (Auth::check()) {
            //1ページ目を表示
            $post = new Post();
            $posts = $post->fetchPage(1);

            return $this->render(
                ['posts' => $posts,
                    'page' => 1,
                    'maxPage' => $post->getPagesNumber(),
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