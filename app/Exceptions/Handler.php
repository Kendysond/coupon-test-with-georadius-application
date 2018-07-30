<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use App\Exceptions\InvalidRequestException;
use App\Exceptions\FailedRequestException;
use App\Exceptions\ResourceNotFoundException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        \App\Exceptions\InvalidRequestException::class,
        \App\Exceptions\FailedRequestException::class,
        \App\Exceptions\ResourceNotFoundException::class,
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

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        // return parent::render($request, $exception);

        if ($exception instanceof FatalThrowableError) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong, contact site administrator.',
            ], 500);
        }
        if ($exception instanceof HttpException) {
            return response()->json([
                'status' => 'error',
                'code' => $exception->getCode(),
                'message' => $exception->getMessage(),
                'data' => $exception->getData() ? $exception->getData() : json_decode("{}")
            ], $exception->getHttpStatusCode());
        }
        if ($exception instanceof MethodNotAllowedHttpException) {
            return response()->json([
                'status' => 'error',
                'message' => 'Missing Route',
            ], 405);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Something went wrong, contact site administrator.',
        ], 500);
        
        return parent::render($request, $exception);
    }
}
