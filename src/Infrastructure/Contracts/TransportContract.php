<?php

namespace Notification\Infrastructure\Contracts;

interface TransportContract
{
    public function send(string $text, int $recepientId): void;
}