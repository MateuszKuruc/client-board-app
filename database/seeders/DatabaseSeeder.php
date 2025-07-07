<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'mati@gmail.com'],
            [
                'name' => 'mati',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );

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
