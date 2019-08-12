<?php

namespace models;

use \Core\DB;

/**
 * ユーザーデータを管理するクラス
 */
class User
{

    public $id;
    public $name;
    public $password;
    public $email;

    /**
     * ユーザーが存在するか確認する
     *
     * @param string $email
     * @return boolean
     */
    public function existUser(string $email): bool
    {
        //1件のみSelectして件数が増えたときのパフォーマンスを向上させる
        $stmt = DB::$connect->prepare(
            'SELECT id FROM users WHERE email =:email LIMIT 1'
        );

        $stmt->bindValue(':email', $email, \PDO::PARAM_STR);

        $stmt->execute();
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return !empty($result);
    }

    /**
     * ユーザーデータを取得する
     *
     * @param string $email
     * @return boolean
     */
    public function fetch(string $email): bool
    {
        //emailに該当するuserを抽出する(emailはUNIQUE)
        $stmt = \Core\DB::$connect->prepare(
            'SELECT id, name, password FROM users WHERE email = :email'
        );

        $stmt->bindValue(':email', $email, \PDO::PARAM_STR);

        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!empty($result)) {
            $this->id = $result['id'];
            $this->name = $result['name'];
            $this->password = $result['password'];
            $this->email = $email;

            return true;
        } else {
            return false;
        }

    }

    /**
     * ユーザーデータを挿入する
     *
     * @param string $name
     * @param string $password
     * @param string $email
     * @return void
     */
    public function insert(string $name, string $password, string $email): void
    {

        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = DB::$connect->prepare(
            'INSERT INTO users (name , password, email ) VALUES(:name , :password, :email)'
        );

        $stmt->bindValue(':name', $name, \PDO::PARAM_STR);
        $stmt->bindValue(':password', $password_hash, \PDO::PARAM_STR);
        $stmt->bindValue(':email', $email, \PDO::PARAM_STR);

        $stmt->execute();

        return;
    }
}