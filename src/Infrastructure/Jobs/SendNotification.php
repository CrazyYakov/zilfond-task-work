<?php

namespace Notification\Infrastructure\Jobs;

use App\Models\Notification;
use App\ValuesObjects\NotificationStatusEnum;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Notification\Infrastructure\Services\TransportService;

class SendNotification implements ShouldQueue
{
    use Queueable;

    public $tries = 5;

    public function __construct(
        private Notification $notification
    ) {}

    public function handle(TransportService $transportService): void
    {
        $transport = $transportService->create($this->notification->transport);

        $transport->send($this->notification->text, $this->notification->recipient_id);

        $this->notification->status = NotificationStatusEnum::SENT;

        $this->notification->save();
    }

    public function failed(?\Throwable $exception): void
    {
        $this->notification->status = NotificationStatusEnum::ERROR;

        $this->notification->save();
    }
}
