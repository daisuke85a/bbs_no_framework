<?php

abstract class Controller
{

    protected $controller_name;
    protected $action_name;

    public function __construct($appplication)
    {
        // substrの-10はControllerが10文字なので後ろの10文字分を取り除くということ
        $this->controller_name = strtolower(substr(get_class($this), 0, -10));
    }

    public function run($action, $params = [])
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

    protected function render($variables = [], $view_file, $template = 'layout.php')
    {

        $defaults =
            [
        ];

        $view = new View($defaults);

        return $view->render($view_file, $variables, $template);

    }

    protected function forward404()
    {
        // TODO:しっかり例外処理を書く
        throw new Exception();
        // throw new HttpNotFoundException('Forwarded 404 page from ' . $this->controller_name . '/' . $this->action_name);
    }

    protected function redirect($url)
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

    }
}