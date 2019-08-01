<?php

class User{

    public $id;
    private $password;
    public $name;
    public $email;

    public function insert($name, $password, $email){

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