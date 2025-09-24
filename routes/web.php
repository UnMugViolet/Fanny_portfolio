<?php

use Illuminate\Support\Facades\Route;
use App\Models\Category;

Route::get('/', function () {
    $categories = Category::orderBy('order')->orderBy('name')->get(['id', 'name', 'slug']);
    return view('app', compact('categories'));
});

// Create a route for each category dynamically
Route::get('/{category:slug}', function (Category $category) {
    $categories = Category::orderBy('order')->orderBy('name')->get(['id', 'name', 'slug']);
    
    // For initial page load, load the full data for better SEO and performance
    $category->load(['projects' => function ($query) {
        $query->with('tools');
    }]);
    
    return view('category', [
        'category' => $category,
        'categories' => $categories,
        'projects' => $category->projects,
    ]);
});

Route::fallback(function () {
    abort(404);
});

