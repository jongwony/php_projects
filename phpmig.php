<?php
use Festiv\Database\Migration;
use Festiv\Foundation\Application;
use Phpmig\Adapter;
use Phpmig\Adapter\Illuminate\Database as DatabaseAdapter;
use Wandu\DI\ContainerInterface;

$basePath = realpath(__DIR__);

$app = new Application(
    null,
    require $basePath . '/config.php'
);
$app->call(require $basePath . '/app/providers.php');
$app->setAsGlobal();
$app->boot();

date_default_timezone_set($app['config']->get('timezone', 'UTC'));

$app->closure('phpmig.adapter', function(ContainerInterface $app) {
    return new DatabaseAdapter($app['db'], 'migrations');
});
$app->instance('phpmig.migrations_path', __DIR__.'/migrations');
$app->instance('phpmig.migrations_template_path', Migration::TEMPLATE_PATH);

return $app;
