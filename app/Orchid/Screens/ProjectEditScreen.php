<?php

namespace App\Orchid\Screens;

use App\Models\Category;
use App\Models\Project;
use App\Models\Tool;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Attach;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Fields\Select;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

class ProjectEditScreen extends Screen
{
    /**
     * @var \App\Models\Project
     */
    public $project;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Project $project): iterable
    {
        $project->loadMissing(['categories:id,name', 'tools:id,name']);

        return [
            'project' => $project
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->project->exists ? 'Modifier le projet' : 'Nouveau projet';
    }

    /**
     * The description is displayed on the user's screen under the heading
     */
    public function description(): ?string
    {
        return $this->project->exists ? 'Modifier les détails du projet' : 'Créer un nouveau projet';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Enregistrer')
                ->icon('check')
                ->method('save'),

            Button::make('Supprimer')
                ->icon('trash')
                ->method('remove')
                ->canSee($this->project->exists)
                ->confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ?'),
        ];
    }


    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::tabs([
                'Contenu' => Layout::rows([
                    Input::make('project.order')
                        ->title('Ordre')
                        ->type('number')
                        ->placeholder(Project::max('order') + 1)
                        ->min(1)
                        ->help('Ordre d\'affichage (plus petit = affiché en premier)'),

                    Input::make('project.title')
                        ->title('Nom')
                        ->placeholder('Titre du projet')
                        ->required()
                        ->help('Titre du projet'),

                    Quill::make('project.description')
                        ->title('Description')
                        ->placeholder('Description du projet')
                        ->required()
                        ->help('Description détaillée du projet'),
                    
                    Select::make('project.status')
                        ->title('Statut')
                        ->options([
                            'draft' => 'Brouillon',
                            'published' => 'Publié',
                            'archived' => 'Archivé',
                        ])
                        ->required()
                        ->default('draft')
                        ->help('Statut de publication du projet'),

                    Select::make('project.author_id')
                        ->title('Auteur')
                        ->fromQuery(User::query()->orderBy('name'), 'name')
                        ->empty('Sélectionnez un auteur')
                        ->placeholder(User::query()->first()?->name ?? 'Sélectionnez un auteur')
                        ->value(old('project.author_id', $this->project->author_id ?? User::query()->value('id')))
                        ->help('Sélectionnez l\'auteur du post'),

                    Select::make('project.categories')
                        ->title('Catégories')
                        ->fromQuery(Category::query()->orderBy('name'), 'name')
                        ->multiple()
                        ->placeholder(Category::first() ?->name ?? 'Aucune catégorie disponible')
                        ->value(old('project.categories', $this->project->categories->pluck('id')->all()))
                        ->help('Assignez des catégories au projet'),

                    Attach::make('project.thumbnail')
                        ->title('Image miniature')
                        ->maxFiles(1)
                        ->acceptedFiles('image/*')
                        ->storage('public')
                        ->path('uploads/images')
                        ->maxSize(2048)
                        ->group('thumbnail')
                        ->targetRelativeUrl()
                        ->help('Téléchargez une image miniature pour le projet'),
                ]),
                'Outils' => Layout::rows([
                    Select::make('project.tools')
                        ->title('Outils utilisés')
                        ->fromQuery(Tool::query()->orderBy('name'), 'name')
                        ->multiple()
                        ->value(old('project.tools', $this->project->tools->pluck('id')->all()))
                        ->placeholder('Sélectionnez les outils utilisés dans ce projet')
                        ->help('Assignez des outils au projet pour indiquer les technologies ou logiciels utilisés'),
                ]),

                'Images' => Layout::rows([
                    Attach::make('project.images')
                        ->title('Galerie d\'images')
                        ->storage('public')
                        ->path('uploads/images')
                        ->maxSize(2048) // 2MB
                        ->maxCount(50)
                        ->multiple()
                        ->acceptedFiles('image/*')
                        ->group('images')
                        ->targetRelativeUrl()
                        ->errorTypeMessage('Fichier invalide. Seules les images sont autorisées.')
                        ->errorMaxSizeMessage('Le fichier est trop volumineux. Taille maximale autorisée : 2MB.')
                        ->help('Téléchargez des images supplémentaires pour la galerie du projet (50 images maximum)'),
                ]),
                'Vidéos' => Layout::rows([
                    Input::make('project.youtube_url')
                        ->title('URL YouTube')
                        ->type('url')
                        ->placeholder('https://www.youtube.com/watch?v=example')
                        ->help('Ajoutez une URL YouTube pour intégrer une vidéo dans le projet'),
                ]),
            ])
        ];
    }

    /**
     * Save the project.
     */
    public function save(Project $project, Request $request)
    {

        $authorId = data_get($request->input('project', []), 'author_id')
            ?? User::query()->value('id');

        if ($authorId !== null) {
            $request->merge([
                'project' => array_merge($request->input('project', []), [
                    'author_id' => $authorId,
                ]),
            ]);
        }

        $request->validate([
            'project.order' => 'nullable|integer|min:1',
            'project.title' => 'required|string|max:255',
            'project.description' => 'required|string',
            'project.status' => 'required|in:draft,published,archived',
            'project.author_id' => 'nullable|exists:users,id',
            'project.categories' => 'nullable|array',
            'project.categories.*' => 'exists:categories,id',
            'project.thumbnail' => 'nullable',
            'project.images' => 'nullable',
            'project.tools' => 'nullable|array',
            'project.youtube_url' => 'nullable|url',
        ]);

        $data = $request->get('project');
        
        if (!isset($data['order']) || $data['order'] === null) {
            $data['order'] = Project::max('order') + 1;
        }

        $project->fill($data)->save();


        $categoryIds = collect(Arr::wrap(data_get($request->input('project', []), 'categories', [])))
            ->filter(fn ($id) => is_numeric($id))
            ->map(fn ($id) => (int) $id)
            ->values()
            ->all();

        $toolIds = collect(Arr::wrap(data_get($request->input('project', []), 'tools', [])))
            ->filter(fn ($id) => is_numeric($id))
            ->map(fn ($id) => (int) $id)
            ->values()
            ->all();

        $project->categories()->sync($categoryIds);
        $project->tools()->sync($toolIds);

        // Handle thumbnail and images (MorphToMany relationship)
        $newThumbnailId = is_array($data['thumbnail']) ? $data['thumbnail'] : [$data['thumbnail']];
        $newImageIds = isset($data['images']) ? (is_array($data['images']) ? $data['images'] : [$data['images']]) : [];

        // Filter out empty values and non-integer IDs
        $newThumbnailId = array_filter($newThumbnailId, function ($id) {
            return !empty($id) && is_numeric($id);
        });
        $newImageIds = array_filter($newImageIds, function ($id) {
            return !empty($id) && is_numeric($id);
        });

        /* 
        * Update  relationships - detach all and reattach the selected ones
        * The deletion of the physical files is handled by the ProjectObserver routine
        */
        if ($project->exists) {
            $project->attachments()->detach();
        }
        if (!empty($newThumbnailId)) {
            $project->attachments()->syncWithoutDetaching($newThumbnailId);
        }
        if (!empty($newImageIds)) {
            $project->attachments()->syncWithoutDetaching($newImageIds);
        }

        Toast::success('Le projet a été enregistré avec succès.');
        
        return redirect()->route('platform.projects');
    }

    /**
     * Remove the project.
     */
    public function remove(Project $project)
    {
        // Fetch related attachments as a collection before deleting the project.
        $imageAttachments = $project->attachments()->get();

        // Detach relationships
        $project->categories()->detach();
        $project->tools()->detach();

        foreach ($imageAttachments as $attachment) {
            if ($attachment !== null) {
                Log::info($attachment);
                $attachment->delete();
            }
        }

        $project->delete();

        Toast::info('Le projet a été supprimé avec succès.');

        return redirect()->route('platform.projects');
    }
}
