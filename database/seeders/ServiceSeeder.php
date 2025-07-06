<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            'Web Development',
            'Meta Ads',
            'Google Ads',
            'Meta + Google Ads',
            'TikTok Ads',
            'Mailing',
            'CRM',
            'Pełna automatyzacja',
            'Webinar',
            'Lejek VSL',
            'Prowadzenie social media'
        ];

        foreach($services as $service) {
            Service::firstOrCreate([
                'name' => $service,
            ]);
        }
    }
}
