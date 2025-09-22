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
        return [
            'categories' => Category::withCount('projects')
                ->orderBy('name')
                ->paginate()
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
            Link::make('Create new')
                ->icon('plus')
                ->route('platform.category.edit')
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
                TD::make('name', 'Name')
                    ->sort()
                    ->cantHide()
                    ->filter(TD::FILTER_TEXT)
                    ->render(function (Category $category) {
                        return Link::make($category->name)
                            ->route('platform.category.edit', $category);
                    }),

                TD::make('slug', 'Slug')
                    ->sort()
                    ->render(function (Category $category) {
                        return '<code>' . $category->slug . '</code>';
                    }),

                TD::make('description', 'Description')
                    ->render(function (Category $category) {
                        return Str::limit($category->description, 50);
                    }),

                TD::make('projects_count', 'Projects')
                    ->sort()
                    ->render(function (Category $category) {
                        return $category->projects_count;
                    }),

                TD::make('created_at', 'Created')
                    ->sort()
                    ->render(function (Category $category) {
                        return $category->created_at->format('M j, Y');
                    }),

                TD::make(__('Actions'))
                    ->align(TD::ALIGN_CENTER)
                    ->width('100px')
                    ->render(function (Category $category) {
                        return DropDown::make()
                            ->icon('options-vertical')
                            ->list([

                                Link::make(__('Edit'))
                                    ->route('platform.category.edit', $category->id)
                                    ->icon('pencil'),

                                Button::make(__('Delete'))
                                    ->icon('trash')
                                    ->confirm(__('Are you sure you want to delete this category?'))
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

        Alert::info(__('Category deleted successfully.'));
    }
}
