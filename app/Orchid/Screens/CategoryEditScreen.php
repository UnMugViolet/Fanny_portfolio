<?php

namespace App\Orchid\Screens;

use App\Models\Category;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Can;

class CategoryEditScreen extends Screen
{
    /**
     * @var Category
     */
    public $category;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Category $category): iterable
    {
        return [
            'category' => $category
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->category->exists ? 'Modifier la catégorie' : 'Nouvelle catégorie';
    }

    /**
     * The description is displayed on the user's screen under the heading
     */
    public function description(): ?string
    {
        return $this->category->exists ? 'Modifier les détails de la catégorie' : 'Créer une nouvelle catégorie';
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
                ->canSee($this->category->exists)
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
                    Input::make('category.order')
                        ->title('Ordre')
                        ->type('number')
                        ->placeholder('0')
                        ->help('Ordre d\'affichage (plus petit = affiché en premier)'),

                    Input::make('category.name')
                        ->title('Nom')
                        ->placeholder('Nom de la catégorie')
                        ->required()
                        ->help('Le nom de la catégorie'),

                    Input::make('category.slug')
                        ->title('Slug')
                        ->placeholder('slug-de-la-categorie')
                        ->help('URL slug pour la catégorie (généré automatiquement si laissé vide)'),

                    CheckBox::make('category.is_main')
                        ->title('Catégorie principale')
                        ->placeholder('Marquer comme catégorie principale')
                        ->help('Les catégories principales sont mises en évidence'),

                    TextArea::make('category.description')
                        ->title('Description')
                        ->placeholder('Description de la catégorie')
                        ->rows(3)
                        ->help('Description optionnelle de la catégorie'),
                ]),
                'SEO' => Layout::rows([
                    Input::make('category.meta_title')
                        ->title('Meta Title')
                        ->placeholder('Titre SEO de la catégorie')
                        ->help('Titre SEO pour les moteurs de recherche (optionnel)'),

                    TextArea::make('category.meta_description')
                        ->title('Meta Description')
                        ->placeholder('Description SEO de la catégorie')
                        ->rows(3)
                        ->help('Description SEO pour les moteurs de recherche (optionnel)'),

                    CheckBox::make('category.no_index')
                        ->title('No Index')
                        ->placeholder('Ne pas indexer cette catégorie')
                        ->help('Empêche les moteurs de recherche d\'indexer cette catégorie'),
                ]),
            ]),
        ];
    }

    /**
     * Save category
     */
    public function save(Request $request, Category $category)
    {
        $request->validate([
            'category.order' => 'nullable|integer|min:0',
            'category.name' => 'required|string|max:255',
            'category.slug' => 'nullable|string|max:255|unique:categories,slug,' . $category->id,
            'category.description' => 'nullable|string',
            'category.meta_title' => 'nullable|string|max:255',
            'category.meta_description' => 'nullable|string|max:1000',
            'category.no_index' => 'sometimes|boolean',
        ]);

        $data = $request->get('category');
        
        // Generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        // Default order to 0 if not provided
        if (empty($data['order'])) {
            $data['order'] = 0;
        }
        if (!isset($data['no_index'])) {
            $data['no_index'] = 0;
        } else {
            $data['no_index'] = 1;
        }

        $category->fill($data)->save();

        Alert::success('Catégorie enregistrée avec succès !');

        return redirect()->route('platform.categories');
    }

    /**
     * Remove category
     */
    public function remove(Category $category)
    {
        $category->delete();

        Alert::info('Catégorie supprimée avec succès.');

        return redirect()->route('platform.categories');
    }
}
