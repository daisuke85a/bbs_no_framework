<?php
class DB
{
    // 1つのDBしか接続しない前提とする
    static $connect;

    public static function connect(): void
    {
        self::$connect = new PDO(DB_DSN, DB_USER, DB_PASSWORD, DB_OPTIONS);

        // PDOの内部でエラーが発生したら例外を発生させる。処理の氏やすさを考慮している
        self::$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

}