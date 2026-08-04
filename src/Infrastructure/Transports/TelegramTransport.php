<?php

namespace Notification\Infrastructure\Transports;

use Notification\Infrastructure\Contracts\TransportContract;
use Override;

final class TelegramTransport implements TransportContract
{
    #[Override]
    public function send(string $text, int $recepientId): void
    {
        // ...
    }
}
