<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public function definition(): array
    {
        $start = $this->faker->dateTimeBetween('now', '+3 months');
        $end = $this->faker->dateTimeBetween($start, '+3 months'); // makes end after start

        return [
            'name' => $this->faker->words(3, true), // more realistic than person name
            'client_id' => Client::inRandomOrder()->value('id'), // required
            'active' => $this->faker->boolean(80), // 80% chance true
            'price' => $this->faker->randomFloat(2, 1000, 10000),
            'start_date' => $start,
            'end_date' => $end,
        ];
    }
}
