<?php

class View
{
    protected $base_dir;

    //
    protected $defaults;

    public function __construct($base_dir, $defaults = []){
        $this->base_dir = $base_dir;
        $this->defaults = $defaults;
    }

    public function render($view_file, $_variables = [])
    {
        $_file = '../views/' . $view_file;

        /* ************************************** */
        /* $_fileに入力するパラメータの準備をする */
        /* ************************************** */

        //配列からシンボルテーブルに変数をインポートする
        $defaults = $this->defaults;
        $defaults += $_variables;
        //配列からシンボルテーブルに変数をインポートする
        extract($defaults);

        /* ************************************** */
        /* 出力情報をバッファに入れる準備をする   */
        /* ************************************** */

        //出力情報をバッファに出力する設定
        ob_start();
        //バッファの自動フラッシュを無効にし、最後にまとめて出力する
        ob_implicit_flush(0);


        //$_fileから出力される文字列をバッファに取得
        require $_file;

        //バッファに格納された文字列を取得してreturnする
        $content = ob_get_clean();
        return $content;
    }

    public function escape($string){
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
}