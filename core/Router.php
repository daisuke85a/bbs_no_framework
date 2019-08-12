<?php

namespace core;

/**
 * ルーティングを担う。
 * 前提条件：publicフォルダに適切な.htaccessを設置すること。
 */
class Router
{
    private $routes = [];

    /**
     * ルーティングの定義を設定する。
     * ルーティング定義の例：
     *  [ ['/' => ['controller' => 'home', 'action'=> 'index'] ],
     *  ['/user/edit' =>  ['controller' => 'user', 'action'=> 'edit']
     *  ['/user/:id' =>   ['controller' => 'user', 'action'=> 'show'] ]
     *
     * 動的パラメータには最初に:をつける
     * Applicationを継承したクラスで呼ばれる想定。
     * @param array $definitions
     */
    public function __construct(array $definitions)
    {
        $this->routes = $this->compileRoutes($definitions);
    }

    /**
     * ルーティング定義を動的パラメータを勘案した正規表現に変換する
     *
     * @param array $definitions
     * @return array
     */
    private function compileRoutes(array $definitions): array
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

    /**
     * ルーティング定義配列にパスをマッチングし、ヒットしたルーティング定義を返却する
     * Applicationクラスから呼ばれる。
     *
     * 返却する値：
     * $params['controller']にコントローラ名が格納される
     * $params['action']にアクション名が格納される
     *
     * @param string $path_info
     * @return array
     */
    public function resolve(string $path_info): array
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

        //ルーティングに１つもマッチしない場合は例外を発生させる
        throw new HttpNotFoundException('Forwarded 404 page from ' . $path_info);

        return [];
    }

}