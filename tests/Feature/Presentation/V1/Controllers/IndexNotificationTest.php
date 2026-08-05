<?php

namespace Tests\Feature\Presentation\V1\Controllers;

use App\Models\Notification;
use App\Models\User;
use App\ValuesObjects\NotificationStatusEnum;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class IndexNotificationTest extends TestCase
{
    protected const string ROUTE_NAME = 'api.v1.notifications.index';

    public static function payloads(): array
    {
        return [
            'without_filters' => [
                'payload' => [],
            ],

            'with_all_filters' => [
                'payload' => [
                    'status' => NotificationStatusEnum::SENT->value,
                    'transport' => 'Email',
                ],
            ],
        ];
    }

    #[DataProvider('payloads')]
    public function testSuccess(array $payload = []): void
    {
        $recipient = User::factory()->create();

        Notification::factory()
            ->for($recipient, 'recipient')
            ->count(30)
            ->create();

        $payload['recipientId'] = $recipient->getKey();

        $route = route(self::ROUTE_NAME, $payload);

        $response = $this->getJson($route);

        $response->assertStatus(200);
    }

    public function testSuccessFilterStatus(): void
    {
        $recipient = User::factory()->create();

        Notification::factory()
            ->for($recipient, 'recipient')
            ->set('status', NotificationStatusEnum::SENT)
            ->count(10)
            ->create();

        Notification::factory()
            ->for($recipient, 'recipient')
            ->set('status', NotificationStatusEnum::ERROR)
            ->count(20)
            ->create();

        $route = route(self::ROUTE_NAME, [
            'recipientId' => $recipient->getKey(),
            'status' => NotificationStatusEnum::SENT->value,
        ]);

        $response = $this->getJson($route);

        $response->assertStatus(200);

        $response->assertJsonCount(10, 'data');
    }
}
