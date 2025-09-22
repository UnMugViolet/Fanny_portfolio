<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Illustration',
                'slug' => 'illustration',
                'is_main' => true,
                'order' => 1,
                'description' => 'Créations artistiques et illustrations graphiques originales'
            ],
            [
                'name' => 'Jeunesse',
                'slug' => 'jeunesse',
                'is_main' => true,
                'order' => 2,
                'description' => 'Projets destinés au public jeune : livres pour enfants, jeux éducatifs'
            ],
            [
                'name' => 'Animation',
                'slug' => 'animation',
                'is_main' => true,
                'order' => 3,
                'description' => 'Projets d\'animation 2D/3D, motion design et vidéos animées'
            ],
            [
                'name' => 'Pages de vie',
                'slug' => 'page-de-vie',
                'is_main' => false,
                'order' => 4,
                'description' => 'Projets explorant des récits de vie, des témoignages et des expériences personnelles'
            ],
            [
                'name' => 'Abécédaire des souvenirs d\'enfance',
                'slug' => 'abecedaire-souvenirs-enfance',
                'is_main' => false,
                'order' => 5,
                'description' => 'Projets explorant les souvenirs d\'enfance à travers des illustrations'
            ],
            [
                'name' => 'Charadesign',
                'slug' => 'charadesign',
                'is_main' => false,
                'order' => 6,
                'description' => 'Conception et design de personnages pour divers médias'
            ]
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(['slug' => $category['slug']], $category);
        }
    }
}
