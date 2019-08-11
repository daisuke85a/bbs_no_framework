<?php

namespace core;

class Message
{
    private $life = 2;
    private $str = "";

    function isset(string $key): bool {

        if (empty(($_SESSION['_msg'][$key]))) {
            return false;
        }
        return true;
    }

    public static function get(): array
    {
        if (empty(($_SESSION['_msg']))) {
            return [];
        }
        return $_SESSION['_msg'];
    }

    public function getStr(): string
    {
        return $this->str;
    }

    public static function set(string $key, string $msg): void
    {
        $MsgInstance = new Message();
        $MsgInstance->str = $msg;

        if (empty($_SESSION['_msg'])) {
            $_SESSION['_msg'] = [];
        }

        $_SESSION['_msg'] += [$key => $MsgInstance];

    }

    // メッセージをセットした後の、次のリクエストに対するResponseをしたあとで、クリアする
    public static function sendRequestFlush(): void
    {
        if (empty(($_SESSION['_msg']))) {
            return;
        }

        foreach ($_SESSION['_msg'] as $key => $msg) {
            //Messageオブジェクト以外の場合はunsetする

            if (gettype($msg) === "object") {
                if (get_class($msg) !== 'core\Message') {
                    unset($_SESSION['_msg'][$key]);
                    continue;
                }
            } else {
                unset($_SESSION['_msg'][$key]);
                continue;
            }
            $msg->life--;
            if ($msg->life === 0) {
                unset($_SESSION['_msg'][$key]);
            }
        }
    }
}