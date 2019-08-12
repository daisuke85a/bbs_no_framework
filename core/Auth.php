<?php

namespace core;

class Auth
{

    private static $user_class = "";

    /**
     * 認証時にSessionに記憶させるユーザークラス名を設定する。
     * 各アプリで独自のUserクラスを使えるように、利用者にてクラス名を設定可能とする
     * 他メソッドを使う前に必ず設定すること。
     * @param string $user_class
     * @return void
     */
    public static function setUserClass(string $user_class)
    {
        self::$user_class = $user_class;
    }

    /**
     * 認証を解除する
     *
     * @return void
     */
    public static function releaseAuthenticate(): void
    {
        unset($_SESSION['user']);
        unset($_SESSION['_token']);
    }

    /**
     * 認証済みのユーザーを返却する
     * 帰り値のクラスはsetUserClassで設定したクラスに動的に変わる
     *
     * @return void
     */
    public static function user()
    {
        return $_SESSION['user'];
    }

    /**
     * 認証する
     *
     * @param string $email
     * @param string $password
     * @return boolean
     */
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
        //セッションハイジャックを防ぐため、ログイン時にセッションIDを再生成する
        session_regenerate_id(true);
        $_SESSION['user'] = $user;

        return true;
    }

    /**
     * ユーザー認証済みかチェックする
     *
     * @param User $user
     * @return boolean
     */
    public static function check(User $user = null): bool
    {

        if (!empty($_SESSION['user'])) {
            return true;
        } else {
            return false;
        }
    }

}