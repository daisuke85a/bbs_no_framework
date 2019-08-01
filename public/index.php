<?php

// envを読み込む
require '../.env';

// coreを読み込む
require '../core/Controller.php';
require '../core/Response.php';
require '../core/View.php';
require '../core/Router.php';
require '../core/DB.php';

// controllersを読み込む
require '../controllers/HomeController.php';
require '../controllers/LoginController.php';
require '../controllers/PostController.php';

// modelsを読み込む
require '../models/User.php';
require '../models/Post.php';

// viewsは読み込まない（表示するときだけ読み込む）
require '../Application.php';

$app = new Application();
$app->run();
