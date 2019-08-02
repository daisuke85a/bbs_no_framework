<?php

class Auth
{
    public static $errors = [];

    public static function authenticate(string $email, string $password): bool
    {

        //emailに該当するuserを抽出する
        $stmt = DB::$connect->prepare(
            'SELECT id, name, password FROM users WHERE email = :email'
        );

        $params =
            [
            ':email' => $email,
        ];

        $stmt->execute($params);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        //userが見つからなかったらエラーを返す
        if (empty($result)) {
            return false;
        }

        //パスワードによる認証を試みる
        if (!password_verify($password, $result['password'])) {
            return false;
        }

        $user = new User();
        $user->id = $result['id'];
        $user->name = $result['name'];
        $user->email = $email;

        //ログイン処理をする
        //TODO::session_startが複数回呼ばれない工夫が必要？
        session_start();
        $_SESSION['user'] = $user;

        var_dump($_SESSION['user']);
        return true;
    }

    public static function check(User $user = null): bool
    {

    }

}