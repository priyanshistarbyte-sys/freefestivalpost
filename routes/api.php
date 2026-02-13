<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\WebhookController;
use App\Http\Controllers\Api\NotificationController;


Route::prefix('v2')->group(function () {
    Route::post('/make-post-by-user', [PostController::class, 'makePostByUser']);
});

Route::post('/webhookPayment', [WebhookController::class, 'webhookPayment']);
Route::post('/webhookPaymentFaild', [WebhookController::class, 'webhookPaymentFailed']);

Route::post('/nodeSideEmailSend', [NotificationController::class, 'nodeSideEmailSend']);
Route::post('/nodeSideSMSSend', [NotificationController::class, 'nodeSideSMSSend']);
Route::post('/nodeSideWhatsAppSMS', [NotificationController::class, 'nodeSideWhatsAppSMS']);
