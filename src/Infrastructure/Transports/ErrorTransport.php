<?php

namespace Notification\Infrastructure\Transports;

use Notification\Infrastructure\Contracts\TransportContract;
use Override;

final class ErrorTransport implements TransportContract
{
    #[Override]
    public function send(string $text, int $recipientId): void
    {
        throw new \Exception('Error send');
    }
}
