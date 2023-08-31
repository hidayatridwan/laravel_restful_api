<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\ApiAuthMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::controller(UserController::class)->group(function () {
    Route::post('/users', 'register');
    Route::post('/users/login', 'login');
});

Route::middleware(ApiAuthMiddleware::class)->group(function () {
    Route::controller(UserController::class)->group(function () {
        Route::get('/users/current', 'get');
        Route::patch('/users/current', 'update');
        Route::delete('/users/logout', 'logout');
    });

    Route::controller(ContactController::class)->group(function () {
        Route::post('/contacts', 'create');
        Route::get('/contacts', 'search');
        Route::get('/contacts/{id}', 'get')->where('id', '[0-9]+');
        Route::put('/contacts/{id}', 'update')->where('id', '[0-9]+');
        Route::delete('/contacts/{id}', 'delete')->where('id', '[0-9]+');
    });
});
