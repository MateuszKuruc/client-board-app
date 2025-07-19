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
        $users = [
            ['email' => 'mati@gmail.com', 'name' => 'Mati'],
            ['email' => 'barti@gmail.com', 'name' => 'Barti'],
            ['email' => 'patryk@gmail.com', 'name' => 'Patryk'],
        ];

        foreach ($users as $u) {
            User::firstOrCreate(
                ['email' => $u['email']],
                [
                    'name' => $u['name'],
                    'password' => bcrypt('password'),
                    'email_verified_at' => now(),
                ]
            );
        }

        $this->call([
            ServiceSeeder::class,
            ClientSeeder::class,
            ProjectSeeder::class,
            PaymentSeeder::class,
            ExpenseSeeder::class,
            LeadSeeder::class,
            TagSeeder::class,
        ]);
    }
}
