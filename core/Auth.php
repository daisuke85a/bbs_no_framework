<?php

class Auth
{
    public static $errors = [];
    private static $session_start = false;

    public static function releaseAuthenticate()
    {
        //session_startが複数回呼ばれないようにする
        if (!Auth::$session_start) {
            session_start();
            Auth::$session_start = true;
        }

        // $_SESSION['user'] = null;
        unset($_SESSION['user']);
    }

    public static function user(): User
    {
        //session_startが複数回呼ばれないようにする
        if (!Auth::$session_start) {
            session_start();
            Auth::$session_start = true;
        }

        return $_SESSION['user'];
    }

    public static function authenticate(string $email, string $password): bool
    {

        $user = new User();

        //userが見つからなかったらエラーを返す
        if (!$user->fetch($email)) {
            Auth::$errors = ["login" => "ユーザーIDとパスワードの組み合わせが違います"];
            return false;
        }

        //パスワードによる認証を試みる
        if (!password_verify($password, $user->password)) {
            Auth::$errors = ["login" => "ユーザーIDとパスワードの組み合わせが違います"];
            return false;
        }

        //ログイン処理をする
        //session_startが複数回呼ばれないようにする
        if (!Auth::$session_start) {
            session_start();
            Auth::$session_start = true;
        }
        $_SESSION['user'] = $user;

        return true;
    }

    public static function check(User $user = null): bool
    {
        //session_startが複数回呼ばれないようにする
        if (!Auth::$session_start) {
            session_start();
            Auth::$session_start = true;

        }

        if (!empty($_SESSION['user'])) {
            return true;
        } else {
            return false;
        }
    }

}