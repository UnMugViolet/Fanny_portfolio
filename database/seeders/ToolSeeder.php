<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tool;

class ToolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        define('PAINT_DEFAULT_DESCRIPTION', 'Outil de peinture traditionnelle');
        define('DRAWING_DEFAULT_DESCRIPTION', 'Outil de dessin traditionnel');

        $tools = [
            [
                'name' => 'Adobe Photoshop',
                'description' => 'Logiciel de retouche photo et de conception graphique',
                'color' => '#31A8FF',
            ],
            [
                'name' => 'Adobe Illustrator',
                'description' => 'Logiciel de graphisme vectoriel et d’illustration',
                'color' => '#FF9A00',
            ],
            [
                'name' => 'Adobe InDesign',
                'description' => 'Design de mise en page et publication',
                'color' => '#FF3366',
            ],
            [
                'name' => 'Sketch',
                'description' => 'Outil de conception numérique pour Mac',
                'color' => '#FDB300',
            ],
            [
                'name' => 'Procreate',
                'description' => 'Illustration numérique sur iPad',
                'color' => '#333333',
            ],
            [
                'name' => 'Blender',
                'description' => 'Suite de création d\'objets 3D',
                'color' => '#E87D0D',
            ],
            [
                'name' => 'Adobe After Effects',
                'description' => 'Logiciel de graphisme animé et d’effets visuels',
                'color' => '#9999FF',
            ],
            [
                'name' => 'Cinema 4D',
                'description' => 'Modélisation 3D, animation et rendu',
                'color' => '#F15A24',
            ],
            [
                'name' => 'Adobe Premiere Pro',
                'description' => 'Logiciel de montage vidéo professionnel',
                'color' => '#9999FF',
            ],
            [
                'name' => 'Fusain',
                'description' => DRAWING_DEFAULT_DESCRIPTION,
                'color' => '#4B4B4B',
            ],
            [
                'name' => 'Crayon de couleur',
                'description' => DRAWING_DEFAULT_DESCRIPTION,
                'color' => '#B2B2B2',
            ],
            [
                'name' => 'Gouache',
                'description' => PAINT_DEFAULT_DESCRIPTION,
                'color' => '#D1A100',
            ],
            [
                'name' => 'Aquarelle',
                'description' => PAINT_DEFAULT_DESCRIPTION,
                'color' => '#00A3E0',
            ],
            [
                'name' => 'Encre',
                'description' => DRAWING_DEFAULT_DESCRIPTION,
                'color' => '#000000',
            ],
            [
                'name' => 'Feutre',
                'description' => DRAWING_DEFAULT_DESCRIPTION,
                'color' => '#7E7E7E',
            ],
            [
                'name' => 'Pastel',
                'description' => DRAWING_DEFAULT_DESCRIPTION,
                'color' => '#E27D60',
            ],
            [
                'name' => 'Acrylique',
                'description' => PAINT_DEFAULT_DESCRIPTION,
                'color' => '#85DCB',
            ],
            [
                'name' => 'Huile',
                'description' => PAINT_DEFAULT_DESCRIPTION,
                'color' => '#E8A87C',
            ]
        ];

        foreach ($tools as $tool) {
            Tool::firstOrCreate($tool);
        }
    }
}
