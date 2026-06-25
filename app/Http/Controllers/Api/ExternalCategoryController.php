<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ExternalCategoryController extends Controller
{
    public function index(Request $request)
    {
        // Security Check
        $apiKey = env('OPENAI_API_KEY', 'default_secret_key_change_me');
        if ($request->header('X-API-Key') !== $apiKey) {
            return response()->json(['error' => 'Unauthorized request.'], 401);
        }

        $categories = Category::active()->select('id', 'name')->orderBy('name')->get();

        return response()->json([
            'success' => true,
            'categories' => $categories
        ]);
    }

    public function store(Request $request)
    {
        // Security Check (consistent with other external APIs)
        $apiKey = env('OPENAI_API_KEY', 'default_secret_key_change_me');
        if ($request->header('X-API-Key') !== $apiKey) {
            return response()->json(['error' => 'Unauthorized request.'], 401);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'nullable|string|in:active,draft'
        ]);

        $category = Category::updateOrCreate(
            ['name' => $validated['name']],
            [
                'slug' => Str::slug($validated['name']),
                'status' => $validated['status'] ?? 'active'
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Category synchronized successfully.',
            'category_id' => $category->id
        ], 201);
    }
}
