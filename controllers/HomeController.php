<?php

namespace Controllers;

use Core\Auth;
use Core\Controller;
use Models\Post;

/**
 * ルートパスに相当するコントローラ
 * 本アプリケーションの基盤となる制御をする
 */
class HomeController extends Controller
{
    /**
     * ホーム画面
     *
     * @return string
     */
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
            //未ログイン
        } else {
            return $this->render(
                [],
                'Login.php'
            );
        }
    }
}