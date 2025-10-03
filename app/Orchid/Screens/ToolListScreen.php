<?php

namespace App\Orchid\Screens;

use App\Models\Tool;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\TD;

class ToolListScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        $tools = Tool::query();

        return [
            'tools' => $tools->paginate()
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Outils';
    }

    /**
     * The description is displayed on the user's screen under the heading
     */
    public function description(): ?string
    {
        return 'Gestion des outils utilisés pour la création de projets';
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
                ->route('platform.tool.create')
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
            Layout::table('tools', [
                TD::make('name', 'Nom')
                    ->sort()
                    ->cantHide()
                    ->render(function (Tool $tool) {
                        return Link::make($tool->name)
                            ->route('platform.tool.edit', $tool);
                    }),

                TD::make('description', 'Description')
                    ->sort()
                    ->render(function (Tool $tool) {
                        return $tool->description;
                    }),
                
                TD::make('color', 'Couleur')
                    ->sort()
                    ->render(function (Tool $tool) {
                        return "<div style='width: 80%; height: 100%; background-color: {$tool->color}; border-radius: 4px; border: 1px solid #ccc; color: #FFF; text-align: center;'>{$tool->color}</div>";
                    }),

                TD::make(__('Actions'))
                    ->align(TD::ALIGN_CENTER)
                    ->width('100px')
                    ->render(function (Tool $tool) {
                        return DropDown::make()
                            ->icon('three-dots-vertical')
                            ->list([

                                Link::make(__('Modifier'))
                                    ->route('platform.tool.edit', $tool->id)
                                    ->icon('pencil'),

                                Button::make(__('Supprimer'))
                                    ->icon('trash')
                                    ->confirm(__('Êtes-vous sûr de vouloir supprimer cet outil ?'))
                                    ->method('remove')
                                    ->parameters([
                                        'id' => $tool->id,
                                    ]),
                            ]);
                    }),
            ]),
        ];
    }
}
