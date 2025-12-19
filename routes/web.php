<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InboundController;
use App\Http\Controllers\InboundDetailController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\OutboundController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WarehouseController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group(function(){
    Route::get('/','index')->name('auth.index');
    Route::post('/','auth')->name('auth.authentication');
});

Route::controller(DashboardController::class)->group(function() {
    Route::get('/dashboard','index')->name('dashboard');
});

Route::resource('users', UserController::class)->names('users');
Route::resource('warehouses',WarehouseController::class)->names('warehouses');
Route::resource('items',ItemController::class)->names('items');
Route::resource('inbounds', InboundController::class)->names('inbounds');
Route::resource('outbounds', OutboundController::class)->names('outbounds');

Route::resource('inbound-details',InboundDetailController::class)->names('inbound-details');
