<?php

namespace Notificaiton\Application\Dto;

readonly class CreateNotificationDto
{
    public function __construct(
        public string $text,
        public int $recipientId,
        public string $transport,
    ) {}
}
