<?php

error_reporting(-1);
ini_set('display_errors', 'On');
session_start();

if (empty($_SESSION["count"])) {
    $_SESSION["count"] = 1;
} else {
    $_SESSION["count"]++;
}
echo ($_SESSION["count"]);

// echo ("だいすけ");
// phpinfo();