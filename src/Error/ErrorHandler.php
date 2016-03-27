<?php
namespace Festiv\Publ\Error;

use Exception;
use Festiv\Foundation\ErrorHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Wandu\Http\Exception\HttpException;
use function Festiv\View\render;

class ErrorHandler implements ErrorHandlerInterface
{
    public function handle(ServerRequestInterface $request, Exception $exception)
    {
        $statusCode = 500;
        $reasonPhrase = 'Internal Server Error';
        if ($exception instanceof HttpException) {
            if ($exception->getBody()) {
                return $exception;
            }
            $statusCode = $exception->getStatusCode();
            $reasonPhrase = $exception->getReasonPhrase();
        }
        if (
            $request->hasHeader('x-requested-with') &&
            $request->getHeaderLine('x-requested-with') === 'XMLHttpRequest'
        ) {
            return [
                'message' => $reasonPhrase,
            ];
        }
        return render('errors/http', compact('statusCode', 'reasonPhrase'));
    }
}
