<?php

namespace Tests\Feature\Presentation\V1\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Queue;
use Notification\Infrastructure\Jobs\SendNotification;
use Override;
use Tests\TestCase;

class CreateNotificationTest extends TestCase
{
    protected const string ROUTE_NAME = 'api.v1.notifications.store';

    public function testSuccessCreate(): void
    {
        Queue::fake(SendNotification::class);

        $recipient = User::factory()->create();

        $route = route(self::ROUTE_NAME);

        $response = $this->postJson($route, [
            'text' => 'test message',
            'transport' => 'Email',
            'recipientId' => $recipient->getKey(),
        ]);

        $response->assertCreated();

        Queue::assertPushed(SendNotification::class);

        $this->assertDatabaseHas('notifications', [
            'text' => 'test message',
            'transport' => 'Email',
            'recipient_id' => $recipient->getKey(),
        ]);
    }
}
