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
                'order' => 1,
                'description' => 'Créations artistiques et illustrations graphiques originales'
            ],
            [
                'name' => 'Jeunesse',
                'slug' => 'jeunesse',
                'order' => 2,
                'description' => 'Projets destinés au public jeune : livres pour enfants, jeux éducatifs'
            ],
            [
                'name' => 'Animation',
                'slug' => 'animation',
                'order' => 3,
                'description' => 'Projets d\'animation 2D/3D, motion design et vidéos animées'
            ],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(['slug' => $category['slug']], $category);
        }
    }
}
