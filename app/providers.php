<?php
use Festiv\Providers\DatabaseProvider;
use Festiv\Providers\ErrorHandleProvider;
use Festiv\Providers\HashProvider;
use Festiv\Providers\LoggerProvider;
use Festiv\Providers\ViewProvider;
use Festiv\Publ\Providers\MailProvider;
use Wandu\DI\ContainerInterface;

return function (ContainerInterface $app)
{
    $app->register(new ViewProvider());
    $app->register(new DatabaseProvider());
    $app->register(new HashProvider());
    $app->register(new LoggerProvider());
    $app->register(new MailProvider());
    $app->register(new ErrorHandleProvider());
};
