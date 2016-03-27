<?php
use Festiv\Foundation\Application;
use Festiv\Foundation\Kernels\HttpRouterKernel;

$basePath = realpath(__DIR__ . '/..');

require $basePath . "/vendor/autoload.php";

$app = new Application(
    new HttpRouterKernel(require $basePath . '/app/routes.php'),
    require $basePath . '/config.php'
);
$app->call(require $basePath . '/app/providers.php');
$app->setAsGlobal();
$app->boot();

date_default_timezone_set($app['config']->get('timezone', 'UTC'));

$app->execute();
