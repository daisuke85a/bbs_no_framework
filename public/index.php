<?php

// coreを読み込む
require '../core/Controller.php';
require '../core/Response.php';
require '../core/View.php';

// controllersを読み込む
require '../controllers/TestController.php';

// modelsを読み込む

// viewsは読み込まない（表示するときだけ読み込む）


require '../Application.php';

$app = new Application();
$app->run();
