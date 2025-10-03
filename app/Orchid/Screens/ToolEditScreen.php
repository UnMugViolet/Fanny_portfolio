<?php

namespace App\Orchid\Screens;

use App\Models\Tool;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Screen;

class ToolEditScreen extends Screen
{
    public $tool;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Tool $tool): iterable
    {
        return [
            'tool' => $tool
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->tool->exists ? 'Modifier l\'outil' : 'Nouvel outil';
    }

    /**
     * The description is displayed on the user's screen under the heading
     */
    public function description(): ?string
    {
        return $this->tool->exists ? 'Modifier les détails de l\'outil' : 'Créer un nouvel outil';
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
                ->canSee($this->tool->exists)
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
                Input::make('tool.name')
                    ->title('Nom')
                    ->placeholder('Nom de l\'outil')
                    ->required(),

                Input::make('tool.description')
                    ->title('Description')
                    ->placeholder('Description de l\'outil')
                    ->required(),
                Input::make('tool.color')
                    ->title('Couleur')
                    ->placeholder('Couleur de l\'outil (ex: #FF5733)')
                    ->required()
                    ->type('color'),
            ])
        ];
    }


    /**
     * Save the tool.
     *
     * @param Tool $tool
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save(Tool $tool, Request $request)
    {
        $request->validate([
            'tool.name' => 'required|string|max:255',
            'tool.description' => 'required|string',
            'tool.color' => [
                'required',
                'string',
                'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/' // Validate hex color code
            ],
        ]);

        $data = $request->input('tool');

        $data['color'] = strtoupper($data['color']);

        $tool->fill($data)->save();
        
        return redirect()->route('platform.tools');
    }
}
