<?php

namespace App\Exceptions;

use App\Elibs\eResponse;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        if ($request->is('api/*')) {
            if ($exception instanceof ValidationException) {
                return parent::render($request, $exception);
            }

            if( !$exception->getMessage() ) {
                switch( $exception->getStatusCode() ) {
                    // not authorized
                    case '403':
                        return eResponse::response(403,'Error, Access was denied !!!');
                        break;

                    // not found
                    case '404':
                        return eResponse::response(404,'Error, The route is not defined !!!');
                        break;

                    // wrong http method
                    case '405':
                        return eResponse::response(405,'Error, The HTTP method not allowed !!!');
                        break;

                    // internal error
                    case '500':
                        return eResponse::response(500,'Sorry, Internal Server Error !!!');
                        break;

                    default:
                        return $this->renderHttpException($exception);
                        break;
                }
            } else {
                // defined in route but method not exist
                return response()->json(['code' => 503, 'message' => $exception->getMessage(), 'data' => ['render' => 'Handler.php']])->setStatusCode(503);
            }
        }

        return parent::render($request, $exception);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param \Illuminate\Http\Request                 $request
     * @param \Illuminate\Auth\AuthenticationException $exception
     *
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest('login');
    }
}
