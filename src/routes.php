<?php
use controllers\home;
use controllers\tribo;
use controllers\fazemos;
use controllers\trabalho;
use controllers\contato;
use controllers\ultima;
use controllers\busca;
use controllers\video;

$app->mount('/', new home());
$app->mount('/a-tribo', new tribo());
$app->mount('/o-que-fazemos', new fazemos());
$app->mount('/trabalhos', new trabalho());
$app->mount('/contato', new contato());
$app->mount('/ultimas', new ultima());
$app->mount('/busca', new busca());
$app->mount('/video', new video());
