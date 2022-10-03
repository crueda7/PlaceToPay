<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $status = array("PENDING", "REJECTED", "OK", "ERROR", "APPROVED");

        return [
            'customer_name' => fake()->firstName(),
            'customer_email' => fake()->email(),
            'customer_mobile' => fake()->phoneNumber(),
            'status' => sort($status),
            'requestId' => fake()->numberBetween($min = 1, $max = 999999),
            'processUrl' => fake()->url(),
        ];
    }
}
