<?php

namespace core;

/**
 * クラスを呼び出した際にそのクラスがPHP上に読み込まれていない場合、自動的にファイルの読み込みを行う
 */
class ClassLoader
{
    private $dirs = [];

    /**
     * loadClassメソッドを__autoload()の実装として登録する
     *
     * @return void
     */
    public function register(): void
    {
        //指定した関数を __autoload() の実装として登録する
        spl_autoload_register([$this, 'loadClass']);
    }

    /**
     * オートロード対象となるディレクトリを指定する
     *
     * @param string $dir
     * @return void
     */
    public function registerDir(string $dir): void
    {
        $this->dirs[] = $dir;
    }

    /**
     * オートロード時にPHPから自動的に呼び出され、クラスファイルの読み込みを行う。
     *
     * @param string $class
     * @return void
     */
    public function loadClass(string $class): void
    {
        foreach ($this->dirs as $dir) {

            $classNamespace = ltrim($class, '\\');
            $file = $dir . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $classNamespace) . '.php';

            if (is_readable($file)) {
                require $file;

                return;
            }
        }
    }
}