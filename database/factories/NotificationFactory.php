<?php

namespace Database\Factories;

use App\Models\Notification;
use App\ValuesObjects\NotificationStatusEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Notification>
 */
class NotificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'text' => $this->faker->text(500),
            'status' => $this->faker->randomElement(NotificationStatusEnum::cases()),
            'transport' => $this->faker->randomElement(['telegram', 'email']),
        ];
    }
}
