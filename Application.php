<?php

class Application
{
    protected $response;
    protected $router;

    public function __construct()
    {
        $this->response = new Response();
        $this->router = new Router($this->registerRoutes());
    }

    protected function registerRoutes()
    {
        return [
            '/' => ['controller' => 'home', 'action' => 'welcome'],
            '/login' => ['controller' => 'login', 'action' => 'login'],
            '/logout' => ['controller' => 'login', 'action' => 'logout'],
            '/post/create' => ['controller' => 'post', 'action' => 'create'],
            '/post/:id/delete' => ['controller' => 'post', 'action' => 'delete'],
            '/post/:id' => ['controller' => 'post', 'action' => 'show'],
            '/test/:id' => ['controller' => 'test', 'action' => 'index'],
            '/test/:id' => ['controller' => 'test', 'action' => 'index'],
        ];
    }

    public function run()
    {

        // TODO:Controllerの特定
        // if文で愚直に判定して$controllerに代入する
        // $controller = new TestController();
        // $action = 'index';
        // $params = [];

        $params = $this->router->resolve($_SERVER['REQUEST_URI']);
        if ($params === false) {
            // TODO A
            throw new Exception("exception");
        }

        $controller = $params['controller'];
        $action = $params['action'];

        // ucfirst — 文字列の最初の文字を大文字にする
        $controller_class = ucfirst($controller) . 'Controller';

        // $controller_classの文字列に相当するクラスのファイルをを探してrequireしnewする
        $controller = $this->findController($controller_class);

        if ($controller === false) {
            // TODO B
            throw new Exception("exception");
        }

        // Controllerクラスの $actionAction を $params を引数に実行する
        $content = $controller->run($action, $params);

        $this->response->content = $content;

        $this->response->send();
    }

    protected function findController($controller_class)
    {
        //class_exists — クラスが定義済みかどうかを確認する
        if (!class_exists($controller_class)) {
            $controller_file = './controllers/' . $controller_class . '.php';

            if (!is_readable($controller_file)) {
                return false;
            } else {
                require_once $controller_file;

                if (!class_exists($controller_class)) {
                    return false;
                }
            }
        }

        var_dump($controller_class);

        return new $controller_class($this);
    }
}
