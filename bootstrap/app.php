<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectUsersTo(fn () => route('admin.dashboard'));
        $middleware->web(append: [\App\Http\Middleware\RecordVisit::class]);
        // Laravel sorts its throttle middleware early; the visit log must wrap it so blocked requests are logged.
        $middleware->prependToPriorityList(
            before: \Illuminate\Routing\Middleware\ThrottleRequests::class,
            prepend: \App\Http\Middleware\RecordVisit::class,
        );
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
