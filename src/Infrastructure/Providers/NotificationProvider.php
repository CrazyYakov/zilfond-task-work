<?php

namespace Notification\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Notification\Infrastructure\Factories\TransportFactory;

class NotificationProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->when(TransportFactory::class)
            ->needs('$namespace')
            ->give('Notification\Infrastructure\Transports');
    }
}