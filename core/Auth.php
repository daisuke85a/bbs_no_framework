<?php

namespace core;

use models;

class Auth
{

    private static $user_class = "";

    // 各アプリで独自のUserクラスを使えるように、利用者からクラス名を設定可能とする
    // 他メソッドを使う前に必ず設定すること。
    public static function setUserClass(string $user_class)
    {
        self::$user_class = $user_class;
    }

    public static function releaseAuthenticate(): void
    {
        // $_SESSION['user'] = null;
        unset($_SESSION['user']);
        unset($_SESSION['_token']);
    }

    public static function user(): \Models\User
    {
        return $_SESSION['user'];
    }

    public static function authenticate(string $email, string $password): bool
    {
        // 本クラスの利用者が事前に設定した
        $user = new self::$user_class();

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