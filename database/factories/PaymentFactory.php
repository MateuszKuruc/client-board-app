<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'amount' => $this->faker->randomFloat(2, 500, 5000),
            'status' => $this->faker->randomElement(['paid', 'pending']),
            'payment_date' => function (array $attributes) {
                return $attributes['status'] === 'paid'
                    ? $this->faker->dateTimeBetween('-3 months', 'now')->format('Y-m-d')
                    : null;
            }
        ];
    }
}
