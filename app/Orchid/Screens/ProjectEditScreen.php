<?php

namespace App\Orchid\Screens;

use App\Models\Category;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Orchid\Support\Facades\Alert;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Attach;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Fields\Relation;
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
                        ->placeholder(Project::max('order') ?? 0 + 1)
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

                    Relation::make('project.author_id')
                        ->title('Auteur')
                        ->fromModel(\App\Models\User::class, 'name')
                        ->displayAppend('name')
                        ->placeholder(User::find(1)->name ?? 'Sélectionnez un auteur')
                        ->default(User::find(1)?->id ? : null)
                        ->help('Sélectionnez l\'auteur du post'),

                    Relation::make('project.categories.')
                        ->title('Catégories')
                        ->fromModel(Category::class, 'name')
                        ->multiple()
                        ->placeholder(Category::first() ?->name ?? 'Aucune catégorie disponible')
                        ->default(Category::first() ?->id ? : null)
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
                    Relation::make('project.tools')
                        ->title('Outils utilisés')
                        ->fromModel(\App\Models\Tool::class, 'name')
                        ->multiple()
                        ->placeholder('Sélectionnez les outils utilisés dans ce projet')
                        ->help('Assignez des outils au projet pour indiquer les technologies ou logiciels utilisés'),
                ]),

                'Images' => Layout::rows([
                    Attach::make('project.images')
                        ->title('Galerie d\'images')
                        ->storage('public')
                        ->path('uploads/images')
                        ->maxSize(2048)
                        ->maxCount(10)
                        ->acceptedFiles('image/*')
                        ->group('images')
                        ->targetRelativeUrl()
                        ->help('Téléchargez des images supplémentaires pour la galerie du projet (10 images maximum)'),
                ]),
            ])
        ];
    }

    /**
     * Save the project.
     */
    public function save(Project $project, \Illuminate\Http\Request $request)
    {
        $request->validate([
            'project.order' => 'nullable|integer|min:0',
            'project.title' => 'required|string|max:255',
            'project.description' => 'required|string',
            'project.status' => 'required|in:draft,published,archived',
            'project.author_id' => 'required|exists:users,id',
            'project.categories' => 'nullable|array',
            'project.categories.*' => 'exists:categories,id',
            'project.thumbnail' => 'nullable',
            'project.thumbnail.*' => 'exists:attachments,id',
            'project.images' => 'nullable|array|max:10',
            'project.images.*' => 'exists:attachments,id',
        ]);

        $data = $request->get('project');

        $project->fill($data)->save();

        // Sync categories
        if (isset($data['categories'])) {
            $project->categories()->sync($data['categories']);
        } else {
            $project->categories()->detach();
        }

        // Handle thumbnail (MorphToMany relationship)
        $currentThumbnailIds = $project->exists ? $project->thumbnail->pluck('id')->toArray() : [];
        $newThumbnailIds = [];
        
        if (!empty($data['thumbnail'])) {
            $newThumbnailIds = is_array($data['thumbnail']) ? $data['thumbnail'] : [$data['thumbnail']];
        }
        
        // Find thumbnails to remove (old ones not in new list)
        $thumbnailsToRemove = array_diff($currentThumbnailIds, $newThumbnailIds);
        
        // Delete removed thumbnail files
        if (!empty($thumbnailsToRemove)) {
            foreach ($thumbnailsToRemove as $attachmentId) {
                $attachment = \Orchid\Attachment\Models\Attachment::find($attachmentId);
                if ($attachment) {
                    $attachment->delete();
                }
            }
        }
        
        // Update thumbnail relationships
        if ($project->exists) {
            $project->thumbnail()->detach();
        }
        if (!empty($newThumbnailIds)) {
            foreach ($newThumbnailIds as $attachmentId) {
                $project->thumbnail()->attach($attachmentId);
            }
        }

        // Handle images (MorphToMany relationship)
        $currentImageIds = $project->exists ? $project->images->pluck('id')->toArray() : [];
        $newImageIds = isset($data['images']) && !empty($data['images']) ? $data['images'] : [];
        
        // Find images to remove (old ones not in new list)
        $imagesToRemove = array_diff($currentImageIds, $newImageIds);
        
        // Delete removed image files
        if (!empty($imagesToRemove)) {
            foreach ($imagesToRemove as $attachmentId) {
                $attachment = \Orchid\Attachment\Models\Attachment::find($attachmentId);
                if ($attachment) {
                    $attachment->delete();
                }
            }
        }
        
        // Update image relationships
        if ($project->exists) {
            $project->images()->detach();
        }
        if (!empty($newImageIds)) {
            foreach ($newImageIds as $attachmentId) {
                $project->images()->attach($attachmentId);
            }
        }

        Toast::success('Le projet a été enregistré avec succès.');
        
        return redirect()->route('platform.project.edit', $project->id);
    }

    /**
     * Remove the project.
     */
    public function remove(Project $project)
    {
        // Get all attachments before detaching to delete physical files
        $thumbnailAttachments = $project->thumbnail;
        $imageAttachments = $project->images;

        // Detach relationships
        $project->categories()->detach();
        $project->tools()->detach();
        $project->thumbnail()->detach();
        $project->images()->detach();

        // Delete physical files and attachment records
        foreach ($thumbnailAttachments as $attachment) {
            $attachment->delete(); // This will delete both the file and the database record
        }

        foreach ($imageAttachments as $attachment) {
            $attachment->delete(); // This will delete both the file and the database record
        }

        // Delete the project
        $project->delete();

        Toast::success('Le projet a été supprimé avec succès.');

        return redirect()->route('platform.projects');
    }
}
