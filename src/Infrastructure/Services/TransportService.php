<?php

namespace Notification\Infrastructure\Services;

use Notification\Infrastructure\Contracts\TransportContract;
use Notification\Infrastructure\Factories\TransportFactory;

class TransportService
{
    public function __construct(
        private TransportFactory $transportFactory
    ) {}

    public function create(string $transport): TransportContract
    {
        return $this->transportFactory->create($transport);
    }

    public function exists(string $transport): bool
    {
        try {
            $this->transportFactory->create($transport);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
