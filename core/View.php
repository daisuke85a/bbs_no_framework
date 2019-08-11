<?php

namespace core;

class View
{
    protected $defaults = [];
    protected $layout_variables = [];

    public function __construct(array $defaults = [])
    {
        $this->defaults = $defaults;
    }

    public function escapeEcho(string $string): void
    {
        echo (htmlspecialchars($string, ENT_QUOTES, 'UTF-8'));
    }

    //レイアウトファイル側に値を設定したい場合、ビューファイル内でsetLayoutVar()メソッドを呼び出すことで値が設定できます
    //例えば、ページのタイトルは実行するアクションによって変わる。しかし、タイトルはレイアウト側に出力する必要がある。
    //そういった場合に設定する
    public function setLayoutVar(string $name, string $value): void
    {
        $this->layout_variables[$name] = $value;
    }

    public function render(string $view_file, array $_variables = [], string $_layout = null): string
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

        //引数でレイアウトが指定されている場合
        if (isset($_layout)) {
            //renderメソッドを再帰呼び出しする
            //_contentというキーで先に読み込んだビューファイルの内容を渡す
            //レイアウトファイル内の適当な場所で$_content変数の内容を出力して1つのHTMLとなり、再度$content変数に格納される。
            $content = $this->render($_layout, $this->layout_variables += ['_content' => $content]);
        }
        return $content;
    }

}