<?php

namespace Database\Seeders;

use App\Models\User;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Factories\LeadFactory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        User::factory()->create();

        $this->call([
            ServiceSeeder::class,
            ClientSeeder::class,
            ProjectSeeder::class,
            PaymentSeeder::class,
            ExpenseSeeder::class,
            LeadSeeder::class,
        ]);
    }
}
