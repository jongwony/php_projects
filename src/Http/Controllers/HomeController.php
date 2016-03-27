<?php
namespace Festiv\Publ\Http\Controllers;

use function Festiv\View\render;
use Wandu\Http\Exception\HttpException;

class HomeController
{
    public function index()
    {
        return render('welcome', [
            'message' => 'Hello Publ...................?',
            'others' => '101010101010',
            'articles' => [
                '11111',
                '22222',
                '33333',
                '444444'
            ]
        ]);
    }

    public function error()
    {
        throw new HttpException(500, 'User Defined Error');
    }

    function __call($name, $arguments = [])
    {
        return $name;
    }
}
