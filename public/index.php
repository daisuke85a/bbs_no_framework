<?php

require '../.env';
require '../bootstrap.php';
require '../BbsApplication.php';

$app = new BbsApplication();
$app->run();