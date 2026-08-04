<?php

namespace Notification\Infrastructure\Factories;

use Notification\Infrastructure\Contracts\TransportContract;

class TransportFactory
{
    public function __construct(
        private string $namespace,
    ) {}

    public function create(string $transport): TransportContract
    {
        $class = $this->namespace . '\\' . $transport . 'Transport';

        if (! class_exists($class)) {
            throw new \Exception("Transport class {$class} does not exist.");
        }

        if (! is_subclass_of($class, TransportContract::class)) {
            throw new \Exception("Transport class {$class} must implement TransportContract.");
        }

        return app($class);
    }
}
