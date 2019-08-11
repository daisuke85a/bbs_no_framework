<?php

namespace core;

class Application
{
    //TODO: 独自クラスの変数宣言時に型づけするのはどうすればいいか？PHPではまだできない？
    private $router;

    public function __construct()
    {
        $this->router = new Router($this->registerRoutes());
        DB::connect();
        session_start();
        error_reporting(-1);
        ini_set('display_errors', 'On');
    }

    protected function registerRoutes(): array
    {
        return [];
    }

    public function run(): void
    {

        // Controllerの特定
        $params = $this->router->resolve($_SERVER['REQUEST_URI']);

        $controller = $params['controller'];
        $action = $params['action'];

        // ucfirst — 文字列の最初の文字を大文字にする
        $controller_class = ucfirst($controller) . 'Controller';

        // $controller_classの文字列に相当するクラスのファイルをを探してrequireしnewする
        $controller = $this->findController($controller_class);

        // Controllerクラスの $actionAction を $params を引数に実行する
        $content = $controller->run($action, $params);

        Response::$content = $content;

        Response::send();

        Message::sendRequestFlush();
    }

    protected function findController(string $controller_class): Controller
    {
        //class_exists — クラスが定義済みかどうかを確認する
        if (!class_exists($controller_class)) {
            $controller_file = './controllers/' . $controller_class . '.php';

            if (!is_readable($controller_file)) {
                throw new Exception("exception");
            } else {
                require_once $controller_file;

                if (!class_exists($controller_class)) {
                    throw new Exception("exception");
                }
            }
        }

        return new $controller_class();
    }
}