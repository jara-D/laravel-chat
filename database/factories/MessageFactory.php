<?php

namespace Database\Factories;

use App\Models\message;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<message>
 */
class MessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $time = $this->faker->dateTimeBetween('-1 years');
        return [
            'chat_id' => $this->faker->numberBetween(1, 10),
            'sender_id' => $this->faker->numberBetween(1, 10),
            'message' => $this->faker->realText(),
            'created_at' => $time,
            'updated_at' => $time,
        ];
    }
}
