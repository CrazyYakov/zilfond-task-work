<?php

use Illuminate\Support\Facades\Route;

Route::prefix('/v1')
    ->name('api.v1.')
    ->group(function () {
        Route::prefix('/notifications')
            ->name('notifications.')
            ->group(base_path('/routes/v1/notification.php'));
    });
