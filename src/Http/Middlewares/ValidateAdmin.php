<?php
namespace Festiv\Publ\Http\Middlewares;

use Closure;
use Psr\Http\Message\ServerRequestInterface;
use function Festiv\Http\Response\redirect;

class ValidateAdmin
{
    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Closure $next
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(ServerRequestInterface $request, Closure $next)
    {
        $session = $request->getAttribute('session');
        if ($session->get('is_login') && $session->get('user')['grant'] == 0) {
            return $next($request);
        }
        return redirect(
            '/auth/login?redirect=' . urlencode($request->getRequestTarget())
        );
    }
}
