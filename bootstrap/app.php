<?php

use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\ManajementWarehouseMiddleware;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Middleware\WarehouseMiddleware;
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
        $middleware->alias([
            'admin' => AdminMiddleware::class,
            'warehouse' => WarehouseMiddleware::class,
            'manajement_warehouse' => ManajementWarehouseMiddleware::class,
            'role' => RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
