<?php

namespace Tests\Feature\Presentation\V1\Controllers;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class ShowNotificationTest extends TestCase
{
    protected const string ROUTE_NAME = 'api.v1.notifications.show';

    public function testSuccess(): void
    {
        $notification = Notification::factory()
            ->for(User::factory(), 'recipient')
            ->create();

        $route = route(self::ROUTE_NAME, ['notification' => $notification]);

        $response = $this->getJson($route);

        $response->assertOk();

        $response->assertJson(function (AssertableJson $json) {
            $json->has('data.recipient')->etc();
        });
    }

    public function assertNotFound(): void
    {
        $route = route(self::ROUTE_NAME, ['notification' => 999]);

        $response = $this->getJson($route);

        $response->assertNotFound();
    }
}
