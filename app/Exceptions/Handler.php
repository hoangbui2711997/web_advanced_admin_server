<?php

namespace App\Exceptions;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Log;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    protected $responseReports = [
        AttributeProvidedInvalidException::class
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param Exception $exception
     * @return void
     * @throws Exception
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  Request  $request
     * @param Exception $exception
     * @return Response
     */
    public function render($request, Exception $exception)
    {
        if ($this->isResponseException($exception)) {
            return $this->handleResponseException($exception);
        }

        if ($exception instanceof UnregisteredSessionException) {
            return $this->unregisteredSessionHandler($request, $exception);
        }
        if ($exception instanceof TokenMismatchException) {
            return $this->handleLoginTokenMismatchException($exception);
        }
        return parent::render($request, $exception);
    }

    protected function unregisteredSessionHandler($request, UnregisteredSessionException $exception)
    {
        Log::warning('EXCEPTION UnregisteredSessionException');

        if ($request->expectsJson()) {
            return response()->json(['error' => $exception->getMessage()], 401);
        }

        return redirect()->guest('login')->withErrors([
            'unregisterd_session' => $exception->getMessage()
        ]);
    }

    protected function handleLoginTokenMismatchException($exception) {
        Log::warning('Login EXCEPTION TokenMismatchException');

        return redirect()->guest('/admin/login')->withErrors([
            'session_timeout' => $exception->getMessage()
        ]);
    }

    protected function isResponseException(Exception $exception)
    {
        foreach ($this->responseReports as $responseReport) {
            if ($exception instanceof $responseReport) {
                return true;
            }
        }
    }

    protected function handleResponseException(Exception $exception)
    {
        return response()->json(['message' => $exception->getMessage()], 500);
    }
}
