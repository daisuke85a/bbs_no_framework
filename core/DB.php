<?php

namespace core;

class DB
{
    static $connect;

    /**
     * DBを接続する。
     * DBは1つしか接続しない前提とする
     * @return void
     */
    public static function connect(): void
    {
        self::$connect = new \PDO(DB_DSN, DB_USER, DB_PASSWORD, DB_OPTIONS);

        // PDOの内部でエラーが発生したら例外を発生させる。処理の氏やすさを考慮している
        self::$connect->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

}