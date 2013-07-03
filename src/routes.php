<?php
use controllers\home;
use controllers\trabalho;

$app->mount('/', new home());
$app->mount('/trabalho', new trabalho());