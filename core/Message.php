<?php

class Message
{
    private $life = 1;
    private $str = "";

    function isset(string $key): bool {

        if (empty(($_SESSION['_msg'][$key]))) {
            return false;
        }
        return true;
    }

    public static function set(string $key, string $msg)
    {
        $MsgInstance = new Message();
        $MsgInstance->str = $msg;

        if (empty($_SESSION['_msg'])) {
            $_SESSION['_msg'] = [];
        }

        $_SESSION['_msg'] += [$key => $MsgInstance];

        var_dump($_SESSION['_msg']);
        var_dump($_SESSION['_msg']["key2"]);
        var_dump($MsgInstance->isset("key2"));
        var_dump($MsgInstance->isset("key3"));

        // $Message = new Message($str);
        // $_SESSION['_msg'][] = $Message;
    }
}