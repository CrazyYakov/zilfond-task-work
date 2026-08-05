<?php

namespace Notification\Application\Dto;

use App\ValuesObjects\NotificationStatusEnum;

readonly class IndexNotificationDto
{
    public function __construct(
        public int $recipientId,
        public ?NotificationStatusEnum $status,
        public ?string $transport,
    ) {}
}
