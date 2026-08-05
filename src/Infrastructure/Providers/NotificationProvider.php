<?php

namespace Notification\Infrastructure\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Notification\Application\Events\NotificationCreated;
use Notification\Infrastructure\Factories\TransportFactory;
use Notification\Infrastructure\Jobs\SendNotification;

class NotificationProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->when(TransportFactory::class)
            ->needs('$namespace')
            ->give('Notification\Infrastructure\Transports');

        Event::listen(
            events: NotificationCreated::class,
            listener: SendNotification::class
        );
    }
}
