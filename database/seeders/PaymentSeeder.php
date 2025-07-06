<?php

namespace Database\Seeders;

use App\Models\Payment;
use App\Models\Project;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Project::all()->each(function ($project) {
            Payment::factory()->count(rand(1, 3))->create([
                'project_id' => $project->id,
            ]);
        });
    }
}
