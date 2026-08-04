<?php

use Illuminate\Support\Facades\Route;
use Notification\Presentation\V1\Controllers\NotificationController;

Route::post('/', [NotificationController::class, 'store']);

Route::get('/', [NotificationController::class, 'index']);

Route::get('/{notificaiton}', [NotificationController::class, 'show']);