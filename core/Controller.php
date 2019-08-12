<?php

namespace core;

/**
 * 全てのコントローラークラスは本クラスを継承すること
 */
abstract class Controller
{

    protected $controller_name = "";
    protected $action_name = "";

    /**
     * コンストラクタ
     */
    public function __construct()
    {
        // substrの-10はControllerが10文字なので後ろの10文字分を取り除くということ
        $this->controller_name = strtolower(substr(get_class($this), 0, -10));
    }

    /**
     * コントローラーのアクションを実行し、表示画面用の文字列を返す
     *  Applicationクラスからコールする想定
     *
     * @param string $action
     * @param array $params
     * @return string
     */
    public function run(string $action, array $params = []): string
    {

        $this->action_name = $action;

        $action_method = $action . 'Action';

        if (!method_exists($this, $action_method)) {
            $this->forward404();
        }

        //ポストリクエストの場合は必ずCsrfTokenが含まれているものとする
        if ($_SERVER["REQUEST_METHOD"] === 'POST') {
            if (CsrfToken::check() === false) {
                $this->forward404();
            }
        }

        $content = $this->$action_method($params);

        return $content;
    }

    /**
     * Viewファイルのrenderを呼び出し、表示画面用の文字列を返す。
     * 自クラスや継承先のコントローラークラスからコールする想定。
     *
     * @param array $variables
     * @param string $view_file
     * @param string $template
     * @return string
     */
    protected function render(array $variables = [], string $view_file, string $template = 'layout.php'): string
    {

        $defaults =
            [
        ];

        $view = new View($defaults);

        return $view->render($view_file, $variables, $template);

    }

    /**
     * 404ページ表示用の例外を発生させる
     * 自クラスや継承先のコントローラークラスからコールする想定。
     *
     * @return void
     */
    protected function forward404(): void
    {
        throw new HttpNotFoundException('Forwarded 404 page from ' . $this->controller_name . '/' . $this->action_name);
    }

    /**
     * 指定したurlにリダイレクトする。
     * 自クラスや継承先のコントローラークラスからコールする想定。
     * 主にPOSTされた時などに使用する。
     *
     * @param string $url
     * @return string
     */
    protected function redirect(string $url): string
    {

        // 絶対URLへのredirectは対応しない
        // if(!preg_match('#https?://#' , $url)){
        //     $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https://' : 'http://';
        //     $host = $_SERVER['HTTP_HOST'];
        //     // $base_url = 考えないことにする

        //     $url = $protocol . $host . $url;
        // }

        Response::$status_code = 302;
        Response::$status_text = 'Found';
        Response::$http_headers['Location'] = $url;

        return "";
    }
}