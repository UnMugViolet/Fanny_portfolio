<?php

use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;
use App\Models\Category;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

// Sitemap generation
Route::get('/sitemap.xml', function () {
    $sitemap = Sitemap::create()
        ->add(Url::create('/'));

    foreach (Category::all() as $category) {
        $sitemap->add(Url::create(route('category.show', [
            'category' => $category->slug,
        ])));
    }
    return $sitemap->toResponse(request());
});

Route::get('/', function () {
    $categories = Category::orderBy('order')->orderBy('name')->get(['id', 'name', 'slug']);
    return view('app', compact('categories'));
});

Route::get('/mentions-legales', function () {
    $categories = Category::orderBy('order')->orderBy('name')->get(['id', 'name', 'slug']);
    return view('legal_notice', compact('categories'));
});
Route::get('/politique-de-confidentialite', function () {
    $categories = Category::orderBy('order')->orderBy('name')->get(['id', 'name', 'slug']);
    return view('privacy_policy', compact('categories'));
});
Route::get('/cgu', function () {
    $categories = Category::orderBy('order')->orderBy('name')->get(['id', 'name', 'slug']);
    return view('cgu', compact('categories'));
});

Route::get('/{category:slug}', [CategoryController::class, 'show'])
    ->where('category', '^(?!admin|access-admin-fanny|dashboard|orchid).*')
    ->name('category.show');

Route::fallback(function () {
    abort(404);
});

