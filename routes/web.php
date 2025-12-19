<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group(function(){
    Route::get('/','index')->name('auth.index');
    Route::post('/','auth')->name('auth.authentication');
});

Route::controller(DashboardController::class)->group(function() {
    Route::get('/dashboard','index')->name('dashboard');
});

Route::resource('users', UserController::class)->names('users');
