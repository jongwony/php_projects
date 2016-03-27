<?php
namespace Festiv\Publ\Http\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use function Festiv\View\render;

class AdminController
{
    /** @var \Wandu\Http\Contracts\SessionInterface */
    protected $session;

    public function __construct(ServerRequestInterface $request)
    {
        $this->session = $request->getAttribute('session');
    }

    public function index()
    {
        return render('admin/index', [
            'user' => $this->session->get('user', []),
        ]);
    }
}
