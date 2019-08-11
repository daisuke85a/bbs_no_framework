<?php

//オートロードする
require '../core/ClassLoader.php';
use Core\ClassLoader;

$loader = new ClassLoader();
$loader->registerDir(dirname(__FILE__) . '');

$loader->register();