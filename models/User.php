<?php

class User
{

    public $id;
    public $name;
    public $password;
    public $email;

    public function fetch($email): bool
    {
        //emailに該当するuserを抽出する(emailはUNIQUE)
        $stmt = DB::$connect->prepare(
            'SELECT id, name, password FROM users WHERE email = :email'
        );

        $stmt->bindValue(':email', $email, PDO::PARAM_STR);

        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

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

    public function insert($name, $password, $email): bool
    {

        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = DB::$connect->prepare(
            'INSERT INTO users (name , password, email ) VALUES(:name , :password, :email)'
        );

        $stmt->bindValue(':name', $name, PDO::PARAM_STR);
        $stmt->bindValue(':password', $password_hash, PDO::PARAM_STR);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);

        $stmt->execute();

        return true;
    }
}