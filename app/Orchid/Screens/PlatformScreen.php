<?php

declare(strict_types=1);

namespace App\Orchid\Screens;

use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;

class PlatformScreen extends Screen
{
    public $name = 'Fanny Portfolio Admin';
    public $description = 'Bienvenue sur le panneau d\'administration de Fanny Portfolio. Explorez les fonctionnalités disponibles pour gérer votre site web.';
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [];
    }

    /**
     * The name of the screen displayed in the header.
     */
    public function name(): ?string
    {
        return 'Get Started';
    }

    /**
     * Display header description.
     */
    public function description(): ?string
    {
        return 'Welcome to your Orchid application.';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]
     */
    public function layout(): iterable
    {
        return [
            Layout::view('dashboard.custom-dashboard'),
        ];
    }
}
