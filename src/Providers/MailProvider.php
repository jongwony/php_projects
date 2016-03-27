<?php
namespace Festiv\Publ\Providers;

use PHPMailer;
use Wandu\DI\ContainerInterface;
use Wandu\DI\ServiceProviderInterface;

class MailProvider implements ServiceProviderInterface
{
    public function boot(ContainerInterface $app)
    {
    }

    public function register(ContainerInterface $app)
    {
        $app->closure(PHPMailer::class, function () {
            $mail = new PHPMailer();
            return $mail;
        });
        $app->alias('mail', PHPMailer::class);
    }
}
