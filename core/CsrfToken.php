<?php

class CsrfToken
{
    public static function check(): bool
    {
        if (empty($_SESSION['_token']) || empty($_POST['_token'])) {
            return false;
        }

        if ($_SESSION['_token'] === $_POST['_token']) {
            return true;
        } else {
            return false;
        }
    }

    public static function publication(): string
    {
        if (empty($_SESSION['_token'])) { //トークンが空なら生成
            $token = bin2hex(openssl_random_pseudo_bytes(24));
            $_SESSION['_token'] = $token;
        } else { //トークンがもともとあればそれを使う
            $token = $_SESSION['_token'];
        }

        return $token;
    }
}