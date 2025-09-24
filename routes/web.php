<?php

use Illuminate\Support\Facades\Route;
use App\Models\Category;

Route::get('/', function () {
    return view('app');
});

// Create a route for each category dynamically
Route::get('/{category:slug}', function (Category $category) {
    return view('category', ['category' => $category]);
});

// API Routes for frontend

Route::prefix('api')->group(function () {
    Route::get('/', function(Category $category) {
        return response()->json([
            'category' => $category,
        ]);
    });
});

Route::prefix('api')->group(function () {
    Route::get('/categories/{category:slug}', function (Category $category) {
        $category->load('projects.tools');
        return response()->json([
            'category' => $category,
            'projects' => $category->projects
        ]);
    });
});


Route::fallback(function () {
    abort(404);
});

