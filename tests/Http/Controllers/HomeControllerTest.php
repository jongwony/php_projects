<?php
namespace Festiv\Http\Controllers;

use Festiv\Publ\TestCase;

class HomeControllerTest extends TestCase
{
    /**
     * @Autowired
     * @var \Festiv\Publ\Http\Controllers\HomeController
     */
    protected $controller;

    public function testIndex()
    {
        $this->controller->index();
        $this->assertContains('Hello Publ', $this->controller->index());
    }
}
