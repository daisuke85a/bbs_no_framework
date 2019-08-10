<?php

class Router
{
    private $routes;

    //$definitionsにはこういうのがはいる
    // [ ['/' => ['controller' => 'home', 'action'=> 'index'] ],
    //   ['/user/edit' =>  ['controller' => 'user', 'action'=> 'edit']
    //   ['/user/:id' =>   ['controller' => 'user', 'action'=> 'show'] ]
    public function __construct($definitions)
    {

        $this->routes = $this->compileRoutes($definitions);
    }

    public function compileRoutes($definitions)
    {
        $routes = [];

        foreach ($definitions as $url => $params) {
            // urlをスラッシュごとに分割する
            $tokens = explode('/', ltrim($url, '/'));
            foreach ($tokens as $i => $token) {
                // 動的パラメータの場合(文字列の最初に:がみつかった場合)
                if (0 === strpos($token, ':')) {
                    //$token[1]から文字列の最後までの部分文字列を返す
                    $name = substr($token, 1);

                    //正規表現の形に変換する
                    // 名前付きキャプチャ
                    // (:P<名前>パターン)とすると指定した名前をキーに取得できるようになる
                    $token = '(?P<' . $name . '>[^/]+)';
                }
                $tokens[$i] = $token;
            }
            // tokensの配列要素を/にて連結する
            // 例 $tokensが['orange','apple','mango']なら、orange/apple/mangoにする
            $pattern = '/' . implode('/', $tokens);
            $routes[$pattern] = $params;
        }

        return $routes;
    }

    public function resolve($path_info)
    {

        // PATH_INFOの先頭がスラッシュでない場合は、先頭にスラッシュを付与する
        if ('/' !== substr($path_info, 0, 1)) {
            $path_info = '/' . $path_info;
        }

        foreach ($this->routes as $pattern => $params) {
            //ルーティング定義配列でマッチングする
            //pattern で指定した正規表現により subject を検索します。
            //matches を指定した場合、検索結果が代入されます。
            //$matches[0] にはパターン全体にマッチしたテキストが代入され、 $matches[1] には 1 番目のキャプチャ用サブパターンにマッチした 文字列が代入され、といったようになります。
            if (preg_match('#^' . $pattern . '$#', $path_info, $matches)) {
                $params += $matches;

                return $params;
            }
        }

        //ルーティングに１つもマッチしない場合はfalseを返す
        return false;
    }

}