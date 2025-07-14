<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Project;
use App\Models\Service;
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
        $end = $this->faker->dateTimeBetween($start, '+3 months');

        return [
            'name' => $this->faker->words(3, true),
            'client_id' => Client::inRandomOrder()->value('id'),
            'service_id' => Service::inRandomOrder()->value('id'),
            'active' => $this->faker->boolean(80),
            'price' => $this->faker->randomFloat(2, 1000, 10000),
            'type' => $this->faker->randomElement(['Subskrypcja', 'Standard']),
            'start_date' => $start,
            'end_date' => $end,
        ];
    }
}
