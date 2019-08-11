<?php

//クラスを呼び出した際にそのクラスがPHP上に読み込まれていない場合、自動的にファイルの読み込みを行う
class ClassLoader
{
    private $dirs = [];

    public function register(): void
    {
        spl_autoload_register([$this, 'loadClass']);
    }

    public function registerDir(string $dir): void
    {
        $this->dirs[] = $dir;
    }

    //オートロード時にPHPから自動的に呼び出され、クラスファイルの読み込みを行う。
    public function loadClass(string $class): void
    {
        foreach ($this->dirs as $dir) {
            $file = $dir . '/' . $class . '.php';
            if (is_readable($file)) {
                require $file;

                return;
            }
        }
    }
}