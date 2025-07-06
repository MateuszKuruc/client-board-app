<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            'VIP',
            'Pilne',
            'Oczekiwanie na decyzję',
            'Problematyczny',
            'Problemy z płatnościami',
            'Długoterminowa współpraca',
            'Kontakt przez telefon',
            'Kontakt przez e-mail',
            'Kontakt przez komunikator',
        ];

        foreach($tags as $tag) {
            Tag::firstOrCreate([
                'name' => $tag,
            ]);
        }
    }
}
