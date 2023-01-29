<?php

namespace Kirbykot\DistributedEvents;

use Illuminate\Contracts\Debug\ExceptionHandler;
use Kirbykot\PhpDistributedMessages\Contracts\ErrorHandler;

class LaravelErrorReporter implements ErrorHandler
{
    private ExceptionHandler $handler;

    public function __construct(ExceptionHandler $handler)
    {
        $this->handler = $handler;
    }

    public function handle(\Throwable $throwable): void
    {
        $this->handler->report($throwable);
    }
}