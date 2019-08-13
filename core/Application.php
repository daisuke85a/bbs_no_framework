<?php

namespace core;

/**
 * アプリケーションの本体となるクラス
 * アプリ作成者は以下のように使用すること
 *  1.本クラスを継承したクラスを作成する
 *  2.registerRoutes()にてルーティングを登録する
 *  3.run()にて実行する
 */
abstract class Application
{
    //TODO: 独自クラスの変数宣言時に型づけするのはどうすればいいか？PHPではまだできない？
    private $router;

    //エラーメッセージを表示させたくない場合はfalseに設定すること
    protected $isDebugMode = true;

    /**
     * コンストラクタ
     */
    public function __construct()
    {
        $this->router = new Router($this->registerRoutes());
        DB::connect();
        session_start();
        error_reporting(-1);
        ini_set('display_errors', 'On');
    }

    /**
     * ルーティングを登録する。
     * 継承先でオーバーライドすること。
     *
     * @return array
     */
    protected function registerRoutes(): array
    {
        return [];
    }

    /**
     * アプリケーションを実行する
     *
     * @return void
     */
    public function run(): void
    {

        try {

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

        } catch (HttpNotFoundException $e) {
            $this->render404Page($e);
        }
        Response::send();

        Message::sendRequestFlush();
    }

    /**
     * 指定されたコントローラー名のクラスをnewし返却する
     *
     * @param string $controller_class
     * @return Controller
     */
    private function findController(string $controller_class): Controller
    {
        //class_exists — クラスが定義済みかどうかを確認する
        $controller_class_with_namespace = 'controllers\\' . $controller_class;

        if (!class_exists($controller_class_with_namespace)) {
            $controller_file = './controllers/' . $controller_class . '.php';
            if (!is_readable($controller_file)) {
                throw new HttpNotFoundException($controller_class . ' controller is not found.');
            } else {
                require_once $controller_file;

                if (!class_exists($controller_class_with_namespace)) {
                    throw new HttpNotFoundException($controller_class . ' controller is not found.');
                }
            }
        }

        return new $controller_class_with_namespace();
    }

    /**
     * 404ページを表示する
     *
     * @param HttpNotFoundException $e
     * @return void
     */
    private function render404Page(HttpNotFoundException $e): void
    {
        Response::$status_code = 404;
        Response::$status_text = 'Not Found';

        $message = $this->isDebugMode ? $e->getMessage() : 'Page not found.';
        $message = \htmlspecialchars($message, ENT_QUOTES, 'UTF-8');

        Response::$content = (<<<EOF
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>404</title>
</head>

<body>
        {$message}
</body>

</html>
EOF
);
    }
}
