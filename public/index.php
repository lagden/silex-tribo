<?php
error_reporting(-1);
require_once __DIR__.'/../vendor/autoload.php';
$debug = true;
$app = require __DIR__.'/../src/app.php';
$app->run();
