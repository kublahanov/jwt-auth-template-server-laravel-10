<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
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
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        // $this->stopIgnoring(HttpException::class);

        $this->reportable(function (Throwable $e) {
            //
        });
    }

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
}
