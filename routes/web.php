<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\WebHookController;

Route::get('/', [PaymentController::class, 'index'])->name('payment.form');
Route::post('/checkout', [PaymentController::class, 'checkout'])->name('payment.checkout');
Route::get('/success', function () {
    return "Payment Successful!";
})->name('payment.success');
Route::get('/cancel', function () {
    return "Payment Canceled!";
})->name('payment.cancel');

Route::post('/webhook',WebHookController::class);
