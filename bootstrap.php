<?php

//オートロードする
require '../core/ClassLoader.php';

$loader = new ClassLoader();
$loader->registerDir(dirname(__FILE__) . '/core');
$loader->registerDir(dirname(__FILE__) . '/models');
$loader->registerDir(dirname(__FILE__) . '/controllers');

$loader->register();