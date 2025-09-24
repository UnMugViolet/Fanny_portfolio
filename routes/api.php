<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Category;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Get all categories
Route::get('/categories', function () {
    return Category::orderBy('order')->orderBy('name')->get(['id', 'name', 'slug']);
});

// Get specific category with projects and tools
Route::get('/categories/{category:slug}', function (Category $category) {
    // Load the category with its projects and tools
    $category->load(['projects' => function ($query) {
        $query->with('tools');
    }]);
    
    return response()->json([
        'category' => $category,
        'projects' => $category->projects
    ]);
});
