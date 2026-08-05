<?php

namespace Tests\Feature\Infrastructure\Jobs;

use App\Models\Notification;
use App\Models\User;
use App\ValuesObjects\NotificationStatusEnum;
use Mockery\MockInterface;
use Notification\Application\Events\NotificationCreated;
use Notification\Infrastructure\Contracts\TransportContract;
use Notification\Infrastructure\Jobs\SendNotification;
use Notification\Infrastructure\Services\TransportService;
use Tests\TestCase;

class SendNotificationTest extends TestCase
{
    public function testSuccess(): void
    {
        $notification = Notification::factory()
            ->for(User::factory(), 'recipient')
            ->set('status', NotificationStatusEnum::PROGRESS)
            ->set('transport', 'Test')
            ->set('text', 'Test notification')
            ->create();

        $mockTransport = $this->createMock(TransportContract::class);

        $mockTransport->expects($this->once())
            ->method('send')
            ->with('Test notification', $notification->recipient_id);

        $this->mock(TransportService::class, function (MockInterface $mock) use ($mockTransport) {
            $mock->shouldReceive('create')
                ->once()
                ->with('Test')
                ->andReturn($mockTransport);
        });

        $notificationCreated = new NotificationCreated($notification);

        SendNotification::dispatchSync($notificationCreated);

        $this->assertDatabaseHas('notifications', [
            'id' => $notification->id,
            'status' => NotificationStatusEnum::SENT,
        ]);
    }

    public function testFailed(): void
    {
        $notification = Notification::factory()
            ->for(User::factory(), 'recipient')
            ->set('status', NotificationStatusEnum::PROGRESS)
            ->set('transport', 'Test')
            ->set('text', 'Test notification')
            ->create();

        $mockTransport = $this->createMock(TransportContract::class);

        $mockTransport->expects($this->once())
            ->method('send')
            ->with('Test notification', $notification->recipient_id)
            ->willThrowException(new \Exception('Transport error'));

        $this->mock(TransportService::class, function (MockInterface $mock) use ($mockTransport) {
            $mock->shouldReceive('create')
                ->once()
                ->with('Test')
                ->andReturn($mockTransport);
        });

        try {
            $notificationCreated = new NotificationCreated($notification);

            SendNotification::dispatchSync($notificationCreated);
        } catch (\Throwable $exception) {
            $this->assertEquals('Transport error', $exception->getMessage());
        }

        $this->assertDatabaseHas('notifications', [
            'id' => $notification->id,
            'status' => NotificationStatusEnum::ERROR,
        ]);
    }
}
