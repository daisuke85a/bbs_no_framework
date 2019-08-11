<?php

class Response
{
    public static $content;
    public static $status_code = 200;
    public static $status_text = 'OK';
    public static $http_headers = [];

    public static function send(): void
    {
        // header — 生の HTTP ヘッダを送信する
        header('HTTP/1.1' . self::$status_code . ' ' . self::$status_text);

        foreach (self::$http_headers as $name => $value) {
            // header — 生の HTTP ヘッダを送信する
            header($name . ': ' . $value);
        }

        echo self::$content;
    }

}