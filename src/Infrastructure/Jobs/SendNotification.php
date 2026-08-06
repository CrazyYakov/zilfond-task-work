<?php

namespace Notification\Infrastructure\Jobs;

use App\ValuesObjects\NotificationStatusEnum;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Notification\Application\Events\NotificationCreated;
use Notification\Infrastructure\Services\TransportService;
use Throwable;

class SendNotification implements ShouldQueue
{
    public int $tries = 5;

    public int $backoff = 4;

    public function __construct(
        private TransportService $transportService
    ) {}

    public function handle(NotificationCreated $notificationCreated): void
    {
        $notification = $notificationCreated->notification;

        $transport = $this->transportService->create($notification->transport);

        $transport->send($notification->text, $notification->recipient_id);

        $notification->status = NotificationStatusEnum::SENT;

        $notification->save();
    }

    public function failed(NotificationCreated $notificationCreated, Throwable $exception): void
    {
        $notification = $notificationCreated->notification;

        $notification->status = NotificationStatusEnum::ERROR;

        $notification->save();

        Log::error('Error sending notification', [
            'message' => $exception->getMessage(),
            'notification' => $notification->toArray(),
        ]);
    }
}
