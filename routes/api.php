<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

Route::post('/makePostByUser', [ApiController::class, 'makePostByUser']);
Route::post('/webhookPayment', [ApiController::class, 'webhookPayment']);
Route::post('/webhookPaymentFaild', [ApiController::class, 'webhookPaymentFailed']);