<?php

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Paystack webhook route (no authentication required)
Route::post('/webhooks/paystack', [App\Http\Controllers\Api\PaystackWebhookController::class, 'handleWebhook'])
    ->name('api.webhooks.paystack')
    ->middleware('throttle:60,1'); // Rate limit webhook requests
