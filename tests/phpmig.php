<?php
use Festiv\Foundation\Application;
use Phpmig\Adapter;
use Phpmig\Adapter\Illuminate\Database as DatabaseAdapter;
use Wandu\DI\ContainerInterface;

$app = new Application(
    null,
    require __DIR__ . '/config.php'
);
$app->call(require __DIR__ . '/../app/providers.php');
$app->setAsGlobal();
$app->boot();

date_default_timezone_set($app['config']->get('timezone', 'UTC'));

$app->closure('phpmig.adapter', function(ContainerInterface $app) {
    return new DatabaseAdapter($app['db'], 'migrations');
});
$app->instance('phpmig.migrations_path', __DIR__ . '/../migrations');

return $app;
