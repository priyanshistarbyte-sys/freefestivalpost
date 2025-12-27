<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\WebhookController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Post creation API
Route::post('/makePostByUser', [PostController::class, 'makePostByUser']);

// Webhook APIs
Route::post('/webhookPayment', [WebhookController::class, 'webhookPayment']);
Route::post('/webhookPaymentFaild', [WebhookController::class, 'webhookPaymentFailed']);