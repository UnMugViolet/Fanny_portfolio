<?php

declare(strict_types=1);

use App\Orchid\Screens\CategoryEditScreen;
use App\Orchid\Screens\CategoryListScreen;
use App\Orchid\Screens\PlatformScreen;
use App\Orchid\Screens\ProjectEditScreen;
use App\Orchid\Screens\ProjectListScreen;
use App\Orchid\Screens\Role\RoleEditScreen;
use App\Orchid\Screens\Role\RoleListScreen;
use App\Orchid\Screens\ToolEditScreen;
use App\Orchid\Screens\ToolListScreen;
use App\Orchid\Screens\User\UserEditScreen;
use App\Orchid\Screens\User\UserListScreen;
use App\Orchid\Screens\User\UserProfileScreen;
use Illuminate\Support\Facades\Route;
use Tabuna\Breadcrumbs\Trail;

/*
|--------------------------------------------------------------------------
| Dashboard Routes
|--------------------------------------------------------------------------
*/

Route::screen('/main', PlatformScreen::class)
    ->name('platform.main')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->push(__('Dashboard'), route('platform.main')));

Route::screen('profile', UserProfileScreen::class)
    ->name('platform.profile')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.main')
        ->push(__('Profile'), route('platform.profile')));

Route::screen('users/{user}/edit', UserEditScreen::class)
    ->name('platform.systems.users.edit')
    ->breadcrumbs(fn (Trail $trail, $user) => $trail
        ->parent('platform.systems.users')
        ->push($user->name, route('platform.systems.users.edit', $user)));

Route::screen('users/create', UserEditScreen::class)
    ->name('platform.systems.users.create')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.systems.users')
        ->push(__('Create'), route('platform.systems.users.create')));

Route::screen('users', UserListScreen::class)
    ->name('platform.systems.users')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.main')
        ->push(__('Users'), route('platform.systems.users')));

Route::screen('roles/{role}/edit', RoleEditScreen::class)
    ->name('platform.systems.roles.edit')
    ->breadcrumbs(fn (Trail $trail, $role) => $trail
        ->parent('platform.systems.roles')
        ->push($role->name, route('platform.systems.roles.edit', $role)));

Route::screen('roles/create', RoleEditScreen::class)
    ->name('platform.systems.roles.create')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.systems.roles')
        ->push(__('Create'), route('platform.systems.roles.create')));

Route::screen('roles', RoleListScreen::class)
    ->name('platform.systems.roles')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.main')
        ->push(__('Roles'), route('platform.systems.roles')));

Route::screen('categories', CategoryListScreen::class)
    ->name('platform.categories')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.main')
        ->push(__('Categories'), route('platform.categories')));

Route::screen('categories/{category}/edit', CategoryEditScreen::class)
    ->name('platform.category.edit')
    ->breadcrumbs(fn (Trail $trail, $category) => $trail
        ->parent('platform.categories')
        ->push($category->name ?? __('Edit'), route('platform.category.edit', $category)));

Route::screen('categories/create', CategoryEditScreen::class)
    ->name('platform.category.create')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.categories')
        ->push(__('Create'), route('platform.category.create')));

Route::screen('projects', ProjectListScreen::class)
    ->name('platform.projects')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.main')
        ->push(__('Projects'), route('platform.projects')));

Route::screen('projects/{project}/edit', ProjectEditScreen::class)
    ->name('platform.project.edit')
    ->breadcrumbs(fn (Trail $trail, $project) => $trail
        ->parent('platform.projects')
        ->push($project->name ?? __('Edit Project'), route('platform.project.edit', $project)));

Route::screen('projects/create', ProjectEditScreen::class)
    ->name('platform.project.create')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.projects')
        ->push(__('Create Project'), route('platform.project.create')));

Route::screen('tools', ToolListScreen::class)
    ->name('platform.tools')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.main')
        ->push(__('Tools'), route('platform.tools')));

Route::screen('tools/{tool}/edit', ToolEditScreen::class)
    ->name('platform.tool.edit')
    ->breadcrumbs(fn (Trail $trail, $tool) => $trail
        ->parent('platform.tools')
        ->push($tool->name ?? __('Edit Tool'), route('platform.tool.edit', $tool)));

Route::screen('tools/create', ToolEditScreen::class)
    ->name('platform.tool.create')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.tools')
        ->push(__('Create Tool'), route('platform.tool.create')));
