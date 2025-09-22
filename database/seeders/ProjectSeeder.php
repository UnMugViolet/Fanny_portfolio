<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Category;
use App\Models\Tool;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $author = User::where('email', 'contact@fanny-seraudie.fr')->first();

        // Get categories
        $illustrationCategory = Category::where('slug', 'illustration')->first();
        $jeunesseCategory = Category::where('slug', 'jeunesse')->first();
        $animationCategory = Category::where('slug', 'animation')->first();

        // Get some tools
        $photoshop = Tool::where('name', 'Adobe Photoshop')->first();
        $illustrator = Tool::where('name', 'Adobe Illustrator')->first();
        $procreate = Tool::where('name', 'Procreate')->first();
        $afterEffects = Tool::where('name', 'Adobe After Effects')->first();
        $aquarelle = Tool::where('name', 'Aquarelle')->first();


        $projects = [
            [
                'title' => 'Série d\'illustrations botaniques',
                'description' => 'Collection d\'illustrations détaillées de plantes et fleurs sauvages, réalisées à l\'aquarelle et finalisées numériquement. Ce projet explore la beauté de la nature à travers un style artistique délicat et coloré.',
                'status' => 'published',
                'author_id' => $author->id,
                'categories' => [$illustrationCategory],
                'tools' => [$photoshop, $procreate, $aquarelle]
            ],
            [
                'title' => 'Livre illustré pour enfants "Les Aventures de Pixel"',
                'description' => 'Illustrations complètes pour un livre jeunesse de 32 pages racontant les aventures d\'un petit robot dans un monde numérique. Style coloré et amical, adapté aux enfants de 6-10 ans.',
                'status' => 'published',
                'author_id' => $author->id,
                'categories' => [$jeunesseCategory, $illustrationCategory],
                'tools' => [$illustrator, $photoshop]
            ],
            [
                'title' => 'Court-métrage animé "Le Voyage des Nuages"',
                'description' => 'Animation 2D de 3 minutes racontant l\'histoire poétique des nuages qui voyagent à travers les saisons. Projet personnel explorant les techniques d\'animation traditionnelle avec une approche moderne.',
                'status' => 'published',
                'author_id' => $author->id,
                'categories' => [$animationCategory],
                'tools' => [$afterEffects, $illustrator, $photoshop]
            ],
            [
                'title' => 'Identité visuelle pour marque éco-responsable',
                'description' => 'Création complète d\'une identité visuelle pour une startup spécialisée dans les produits écologiques : logo, charte graphique, applications sur différents supports.',
                'status' => 'published',
                'author_id' => $author->id,
                'categories' => [$illustrationCategory],
                'tools' => [$illustrator]
            ],
            [
                'title' => 'Jeu éducatif interactif "Apprendre les Couleurs"',
                'description' => 'Interface et illustrations pour une application mobile éducative destinée aux enfants de 3-6 ans. Design ludique et intuitif pour l\'apprentissage des couleurs primaires et secondaires.',
                'status' => 'draft',
                'author_id' => $author->id,
                'categories' => [$jeunesseCategory, $illustrationCategory],
                'tools' => [$illustrator, $procreate]
            ],
        ];

        foreach ($projects as $projectData) {
            // Extract relationships data
            $categories = $projectData['categories'] ?? [];
            $tools = $projectData['tools'] ?? [];
            unset($projectData['categories'], $projectData['tools']);

            // Create or find the project
            $project = Project::firstOrCreate($projectData);

            // Attach categories
            if (!empty($categories)) {
                $categoryIds = collect($categories)->pluck('id')->toArray();
                $project->categories()->sync($categoryIds);
            }

            // Attach tools with pivot data
            if (!empty($tools)) {
                $toolIds = [];
                foreach ($tools as $tool) {
                    if ($tool && is_object($tool) && $tool->id) {
                        $toolIds[] = $tool->id;
                    }
                }
                if (!empty($toolIds)) {
                    $project->tools()->sync($toolIds);
                }
            }
        }
    }
}
