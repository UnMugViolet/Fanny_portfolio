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
            Layout::rows([
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
            ])
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
        ]);

        $data = $request->get('category');
        
        // Generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        // Handle checkbox value - more flexible approach
        $data['is_main'] = !empty($data['is_main']);
        
        // Set default order if not provided
        if (empty($data['order'])) {
            $data['order'] = 0;
        }

        $category->fill($data)->save();

        Alert::info('Catégorie enregistrée avec succès.');

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
