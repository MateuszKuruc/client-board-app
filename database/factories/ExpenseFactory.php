<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Expense>
 */
class ExpenseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word() . ' subscription',
            'amount' => $this->faker->randomFloat(2, 10, 500),
            'is_paid' => $this->faker->boolean(),
            'type' => $this->faker->randomElement(['Miesięczna', 'Roczna', 'Jednorazowa']),
            'payment_date' => $this->faker->boolean(70) ? $this->faker->date() : null,
        ];
    }
}
