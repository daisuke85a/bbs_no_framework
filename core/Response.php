<?php

class Response{
    static public $content;
    static public $status_code = 200;
    static public $status_text = 'OK';
    static public $http_headers = [];

    static public function send(){
        // header — 生の HTTP ヘッダを送信する
        header('HTTP/1.1' . self::$status_code . ' ' . self::$status_text);

        foreach(self::$http_headers as $name => $value){
            // header — 生の HTTP ヘッダを送信する
            header($name . ': ' . $value);
        }

        echo self::$content;
    }

    
}