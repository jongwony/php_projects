<?php
namespace Festiv\Publ;

use Festiv\Foundation\Application;
use Festiv\Foundation\Kernels\TestingKernel;
use Festiv\Testing\AssertsTrait;
use Mockery;
use PHPUnit_Framework_TestCase;
use Symfony\Component\Process\Process;

class TestCase extends PHPUnit_Framework_TestCase
{
    use AssertsTrait;

    /** @var \Wandu\DI\ContainerInterface */
    protected $container;

    public function setUp()
    {
        $app = new Application(
            new TestingKernel($this),
            require __DIR__ . '/config.php'
        );
        $app->call(require __DIR__ . '/../app/providers.php');
        $app->boot();
        $app->setAsGlobal();
        $app->execute();

        $process = new Process('../vendor/bin/phpmig rollback -t 0 && ../vendor/bin/phpmig migrate', __DIR__);
        $process->run();

        $this->container = $app;
    }

    public function tearDown()
    {
        Mockery::close();
    }
}
