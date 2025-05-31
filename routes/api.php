<?php

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\LogoutController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Profile\CurrentUserController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')
    ->name('auth.')
    ->group(function () {
        Route::post('register', RegisterController::class)
            ->name('register');

        Route::post('login', LoginController::class)
            ->name('login');

        Route::post('logout', LogoutController::class)
            ->name('logout')
            ->middleware('auth:sanctum');
    });

Route::prefix('profile')
    ->name('profile.')
    ->group(function () {
        Route::get('current-user', CurrentUserController::class)
            ->name('current-user')
            ->middleware('auth:sanctum');
    });
