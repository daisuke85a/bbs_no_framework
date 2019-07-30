<?php

class Application
{
    protected $response;

    public function __construct(){
        $this->response = new Response();
    }

    public function run(){

        // TODO:Controllerの特定
        // if文で愚直に判定して$controllerに代入する
        $controller = new TestController();
        $action = 'index';
        $params = [];

        $content = $controller->run($action, $params);

        $this->response->content = $content;

        $this->response->send();
    }
}