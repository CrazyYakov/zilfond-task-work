<?php

declare(strict_types= 1);

namespace Notification\Application\Actions;

use App\Models\Notification;
use Notificaiton\Application\Dto\CreateNotificationDto;
use Notification\Infrastructure\Jobs\SendNotification;

class CreateNotificationAction
{
    public function execute(CreateNotificationDto $notificationDto): Notification
    {
        $notification = new Notification();
        $notification->text = $notificationDto->text;
        $notification->recipient_id = $notificationDto->recipientId;
        $notification->transport = $notificationDto->transport;
        $notification->save();

        SendNotification::dispatch($notification);

        return $notification;
    }
}
