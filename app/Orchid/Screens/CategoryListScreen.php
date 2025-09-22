<?php

namespace App\Orchid\Screens;

use App\Models\Category;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Support\Facades\Alert;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryListScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        $categories = Category::query();

        // Handle name filter
        if (request()->has('filter.name')) {
            $categories->where('name', 'like', '%' . request('filter.name') . '%');
        }

        // Handle sorting
        $sort = request('sort', 'order');
        $direction = 'asc';
        
        if (str_starts_with($sort, '-')) {
            $direction = 'desc';
            $sort = substr($sort, 1);
        }

        $categories->orderBy($sort, $direction);

        // If no specific sort is applied, add secondary sort by name
        if ($sort !== 'name') {
            $categories->orderBy('name', 'asc');
        }

        return [
            'categories' => $categories->paginate()
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Catégories';
    }

    /**
     * The description is displayed on the user's screen under the heading
     */
    public function description(): ?string
    {
        return 'Gestion des catégories';
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
                ->route('platform.category.create')
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
            Layout::table('categories', [
                TD::make('order', 'Ordre')
                    ->sort()
                    ->width('100px')
                    ->render(function (Category $category) {
                        return $category->order ?? 0;
                    }),

                TD::make('name', 'Nom')
                    ->sort()
                    ->cantHide()
                    ->filter(TD::FILTER_TEXT)
                    ->render(function (Category $category) {
                        return Link::make($category->name)
                            ->route('platform.category.edit', $category);
                    }),

                TD::make('slug', 'Slug')
                    ->sort()
                    ->width('150px')
                    ->render(function (Category $category) {
                        return Link::make($category->slug)
                            ->href('/' . $category->slug)
                            ->target('_blank')
                            ->class('font-monospace text-decoration-none')
                            ->style('padding: 2px 6px; border-radius: 4px; background-color: #90EE90;');
                    }),

                TD::make('is_main', 'Principal')
                    ->sort()
                    ->render(function (Category $category) {
                        return $category->is_main ?
                            '<span style="color: #059669; font-weight: bold;">✓ Oui</span>' :
                            '<span style="color: #6b7280; font-weight: bold;">✘ Non</span>';
                    }),

                TD::make('description', 'Description')
                    ->render(function (Category $category) {
                        return Str::limit($category->description, 50);
                    }),

                TD::make('created_at', 'Créé le')
                    ->sort()
                    ->render(function (Category $category) {
                        return $category->created_at->format('j M Y');
                    }),


                TD::make(__('Actions'))
                    ->align(TD::ALIGN_CENTER)
                    ->width('100px')
                    ->render(function (Category $category) {
                        return DropDown::make()
                            ->icon('three-dots-vertical')
                            ->list([

                                Link::make(__('Modifier'))
                                    ->route('platform.category.edit', $category->id)
                                    ->icon('pencil'),

                                Button::make(__('Supprimer'))
                                    ->icon('trash')
                                    ->confirm(__('Êtes-vous sûr de vouloir supprimer cette catégorie ?'))
                                    ->method('remove')
                                    ->parameters([
                                        'id' => $category->id,
                                    ]),
                            ]);
                    }),
            ])
        ];
    }

    /**
     * Remove category
     */
    public function remove(Request $request): void
    {
        Category::findOrFail($request->get('id'))->delete();

        Alert::info(__('Catégorie supprimée avec succès.'));
    }
}
