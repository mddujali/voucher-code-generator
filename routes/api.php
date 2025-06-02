<?php

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\LogoutController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Profile\CurrentUserController;
use App\Http\Controllers\Api\Vouchers\GenerateVoucherController;
use App\Http\Controllers\Api\Vouchers\DiscardVoucherController;
use App\Http\Controllers\Api\Vouchers\ShowVouchersController;
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

Route::middleware('auth:sanctum')
    ->group(function () {
        Route::prefix('profile')
            ->name('profile.')
            ->group(function () {
                Route::get('current-user', CurrentUserController::class)
                    ->name('current-user');
            });

        Route::prefix('vouchers')
            ->name('vouchers.')
            ->group(function () {
                Route::get('', ShowVouchersController::class)
                    ->name('list');

                Route::post('generate', GenerateVoucherController::class)
                    ->name('generate');

                Route::delete('{voucher_id}', DiscardVoucherController::class)
                    ->name('discard');
            });
    });
