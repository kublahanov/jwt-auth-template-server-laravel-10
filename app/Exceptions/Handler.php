<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A map of exceptions with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [];

    /**
     * Get the default context variables for logging.
     *
     * @return array<string, mixed>
     */
    protected function context(): array
    {
        return parent::context();

        // return array_merge(parent::context(), [
        //     'foo' => 'bar',
        // ]);
    }

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->stopIgnoring(HttpException::class);

        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function (Throwable $e, $request) {
            // if ($e instanceof ValidationException) {
            //     return response()->json([
            //         'error' => 'ValidationException',
            //         'message' => $e->getMessage(),
            //         'errors' => $e->errors(),
            //     ], $e->status);
            // }

            // if ($e instanceof HttpException) {
            //     return response()->json([
            //         'error' => class_basename($e),
            //         'message' => $e->getMessage(),
            //         'trace' => config('app.debug') ? $e->getTrace() : [],
            //     ], $e->getStatusCode());
            // }

            // if ($e instanceof HttpResponseException) {
            //     return response()->json([
            //         'error' => class_basename($e),
            //         'message' => $e->getMessage(),
            //         'trace' => config('app.debug') ? $e->getTrace() : [],
            //     ], $e->getResponse()->getStatusCode());
            // }

            // dump($e);
            // dd($e instanceof AuthException);

            if ($e instanceof AuthException) {
                return response()->json([
                    'error' => class_basename($e),
                    'message' => $e->getMessage(),
                    'data' => $request->all(),
                ], $e->getCode());
            }

            return response()->json([
                'error' => class_basename($e),
                'message' => $e->getMessage(),
                'trace' => config('app.debug') ? $e->getTrace() : [],
            ], $e->getCode());
        });
    }
}
