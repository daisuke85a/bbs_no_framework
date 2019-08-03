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

        $params =
            [
            ':email' => $email,
        ];

        $stmt->execute($params);
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

    public function insert($name, $password, $email)
    {

        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = DB::$connect->prepare(
            'INSERT INTO users (name , password, email ) VALUES(:name , :password, :email)'
        );

        $params =
            [':name' => $name,
            ':password' => $password_hash,
            ':email' => $email,
        ];

        $stmt->execute($params);

        return $stmt;
    }
}