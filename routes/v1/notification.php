<?php

use Illuminate\Support\Facades\Route;
use Notification\Presentation\V1\Controllers\NotificationController;

Route::post('/', [NotificationController::class, 'store'])->name('store');

Route::get('/', [NotificationController::class, 'index'])->name('index');

Route::get('/{notification}', [NotificationController::class, 'show'])->name('show');
