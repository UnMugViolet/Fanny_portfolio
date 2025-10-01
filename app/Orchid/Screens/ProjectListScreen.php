<?php

namespace App\Orchid\Screens;

use App\Models\Project;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Support\Facades\Layout;
use Illuminate\Support\Str;
use Orchid\Support\Facades\Toast;


class ProjectListScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        $projects = Project::query();
        
        // Handle title filter
        if (request()->has('filter.title')) {
            $projects->where('title', 'like', '%' . request('filter.title') . '%');
        }

        // Handle sorting
        $sort = request('sort', 'order');
        $direction = 'asc';

        if (str_starts_with($sort, '-')) {
            $direction = 'desc';
            $sort = substr($sort, 1);
        }

        $projects->orderBy($sort, $direction);

        // If no specific sort is applied, add secondary sort by title
        if ($sort !== 'title') {
            $projects->orderBy('title', 'asc');
        }

        return [
            'projects' => $projects->paginate()
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Projets';
    }

    /**
     * The description is displayed on the user's screen under the heading\
     * @return string|null
     */
    public function description(): ?string
    {
        return 'Liste des projets créés';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Ajouter')
                ->icon('plus')
                ->route('platform.project.create')
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
            Layout::table('projects', [

                TD::make('order', 'Ordre')
                    ->sort()
                    ->width('100px')
                    ->render(function (Project $project) {
                        return $project->order ?? 0;
                    }),

                TD::make('thumbnail', 'Vignette')
                    ->render(function (Project $project) {
                        return empty($project->thumbnail) ? '<img src="' . $project->thumbnail . '" alt="Thumbnail" style="width: 50px; height: auto;">' : 'N/A';
                    }),

                TD::make('name', 'Titre')
                    ->sort()
                    ->cantHide()
                    ->filter(TD::FILTER_TEXT)
                    ->render(function (Project $project) {
                        return Link::make($project->title)
                            ->route('platform.project.edit', $project->id);
                    }),

                TD::make('description', 'Description')
                    ->render(function (Project $project) {
                        return Str::limit($project->description, 50);
                    }),

                TD::make('created_at', 'Créé le')
                    ->sort()
                    ->render(function (Project $project) {
                        return $project->created_at->format('j M Y');
                    }),

                TD::make('status', 'Statut')
                    ->sort()
                    ->render(function (Project $project) {
                        switch ($project->status) {
                            case 'draft':
                                return '<span style="color: #b91c1c;">&#x1F4DD; Brouillon</span>'; // 📝
                            case 'published':
                                return '<span style="color: #16a34a;">&#x2705; Publié</span>'; // ✅
                            case 'archived':
                                return '<span style="color: #64748b;">&#x1F5C3; Archivé</span>'; // 🗃️
                            default:
                                return '<span style="color: #64748b;">Inconnu</span>';
                        }
                    }),

                TD::make(__('Actions'))
                    ->align(TD::ALIGN_CENTER)
                    ->width('100px')
                    ->render(function (Project $project) {
                        return DropDown::make()
                            ->icon('three-dots-vertical')
                            ->list([

                                Link::make(__('Modifier'))
                                    ->route('platform.project.edit', $project->id)
                                    ->icon('pencil'),

                                Button::make(__('Supprimer'))
                                    ->icon('trash')
                                    ->confirm(__('Êtes-vous sûr de vouloir supprimer ce projet ?'))
                                    ->method('remove')
                                    ->parameters([
                                        'id' => $project->id,
                            ]),
                        ]);
                }),
            ])
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Project $project
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function remove(Project $project)
    {
        $project->delete();

        Toast::info('Le projet a été supprimé avec succès.');

        return redirect()->route('platform.projects');
    }
}
