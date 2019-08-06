<?php

class Auth
{
    public static $errors = [];

    public static function releaseAuthenticate()
    {
        // $_SESSION['user'] = null;
        unset($_SESSION['user']);
        unset($_SESSION['_token']);
    }

    public static function user(): User
    {
        return $_SESSION['user'];
    }

    public static function authenticate(string $email, string $password): bool
    {

        $user = new User();

        //userが見つからなかったらエラーを返す
        if (!$user->fetch($email)) {
            Message::set('login', 'ユーザーIDとパスワードの組み合わせが違います');
            return false;
        }

        //パスワードによる認証を試みる
        if (!password_verify($password, $user->password)) {
            Message::set('login', 'ユーザーIDとパスワードの組み合わせが違います');
            return false;
        }

        //ログイン処理をする
        $_SESSION['user'] = $user;

        return true;
    }

    public static function check(User $user = null): bool
    {

        if (!empty($_SESSION['user'])) {
            return true;
        } else {
            return false;
        }
    }

}