<?php

namespace core;

/**
 * フラッシュメッセージを管理する
 * 主に通知、エラーメッセージの管理を想定している。
 */
class Message
{
    private $life = 2;
    private $str = "";

    /**
     * keyに対応するメッセージが保存されているか確認する
     *
     * @param string $key
     * @return boolean
     */
    function isset(string $key): bool {

        if (empty(($_SESSION['_msg'][$key]))) {
            return false;
        }
        return true;
    }

    /**
     * メッセージを全て取得する。
     * ($key => $MsgInstance)の配列が取得できる。
     *
     * @return array
     */
    public static function get(): array
    {
        if (empty(($_SESSION['_msg']))) {
            return [];
        }
        return $_SESSION['_msg'];
    }

    /**
     * メッセージに格納されている文字列を取得する
     * 例：IDが未入力です。
     * @return string
     */
    public function getStr(): string
    {
        return $this->str;
    }

    /**
     * メッセージを格納する。
     * メッセージは配列として複数保持される。
     * メッセージをセットした後の、次のリクエストに対するResponseをしたあとで、自動的にクリアされる
     * @param string $key
     * @param string $msg
     * @return void
     */
    public static function set(string $key, string $msg): void
    {
        $MsgInstance = new Message();
        $MsgInstance->str = $msg;

        if (empty($_SESSION['_msg'])) {
            $_SESSION['_msg'] = [];
        }

        $_SESSION['_msg'] += [$key => $MsgInstance];

    }

    /**
     * メッセージをセットした後の、次のRequestに対するResponseをしたあとで、クリアする
     * Applicationクラスにて、Responseを返信した後でコールされる。
     *
     * @return void
     */
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