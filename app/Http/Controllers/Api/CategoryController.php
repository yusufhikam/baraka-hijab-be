<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\CategoryResource;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function __construct(protected CategoryService $categoryService) {}


    public function index()
    {
        return response()->json([
            'status' => true,
            'message' => 'Categories fetched successfully',
            'data' => CategoryResource::collection($this->categoryService->getCategories())
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $category = $this->categoryService->create($validated);

        return new CategoryResource($category);
    }
}