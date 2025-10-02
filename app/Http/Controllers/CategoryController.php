<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Project;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    /**
     * Display all the categories.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $categories = Category::orderBy('order')->orderBy('name')->get(['id', 'name', 'slug']);
        return view('categories.index', compact('categories'));
    }

    /**
     * Display a listing of the categories attached with the projects and there tools.
     *
     * @return \Illuminate\View\View
     */
    public function show($category)
    {
        $categoryModel = Category::where('slug', $category)->firstOrFail();
        
        $projects = $categoryModel->projects()
            ->with([
                'tools',
                'categories:id,name,slug',
                'thumbnail',
                'images',
            ])
            ->orderBy('order', 'asc')
            ->get();
        
        return view('category', [
            'projects' => $projects,
            'categories' => Category::orderBy('order')->orderBy('name')->get(['id', 'name', 'slug']),
            'category' => $categoryModel,
        ]);
    }
}
