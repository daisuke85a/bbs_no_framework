<?php

//オートロードする
require '../core/ClassLoader.php';
use core\ClassLoader;

$loader = new ClassLoader();
$loader->registerDir(dirname(__FILE__) . '');

$loader->register();

\core\Auth::setUserClass('\models\User');