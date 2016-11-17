<?php

require_once "vendor/autoload.php";


$app = new \Slim\App();


(new Dotenv\Dotenv(__DIR__))->load();

$container = $app->getContainer();



$app->group("/dashboard", function () use ($app) {

});


$app->group("/admin", function () use ($app) {

});

$app->run();
