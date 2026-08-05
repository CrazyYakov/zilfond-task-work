<?php

namespace Notification\Infrastructure\Jobs;

use App\ValuesObjects\NotificationStatusEnum;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Notification\Application\Events\NotificationCreated;
use Notification\Infrastructure\Services\TransportService;

class SendNotification implements ShouldQueue
{
    use Queueable;

    public int $tries = 5;

    public function __construct(
        private NotificationCreated $notificationCreated
    ) {}

    public function handle(TransportService $transportService): void
    {
        $notification = $this->notificationCreated->notification;

        $transport = $transportService->create($notification->transport);

        $transport->send($notification->text, $notification->recipient_id);

        $notification->status = NotificationStatusEnum::SENT;

        $notification->save();
    }

    public function failed(?\Throwable $exception): void
    {
        $notification = $this->notificationCreated->notification;

        $notification->status = NotificationStatusEnum::ERROR;

        $notification->save();
    }
}
