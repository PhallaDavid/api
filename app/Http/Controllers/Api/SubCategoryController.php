<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubCategory;

class SubCategoryController extends Controller
{
    public function index()
    {
        $subcategories = SubCategory::with('parent', 'products')->get();
        return response()->json([
            'message' => 'SubCategory index',
            'subcategories' => $subcategories
        ]);
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image',
            'is_active' => 'boolean',
            'parent_id' => 'required|exists:categories,id'
        ]);
        $subcategory = SubCategory::create($validated);
        return response()->json([
            'message' => 'SubCategory created successfully',
            'subcategory' => $subcategory
        ], 201);
    }
    public function show($id)
    {
        $subcategory = SubCategory::with('parent', 'products')->find($id);
        if (!$subcategory) {
            return response()->json(['message' => 'SubCategory not found'], 404);
        }
        return response()->json($subcategory);
    }

    public function update(Request $request, $id)
    {
        $subcategory = SubCategory::find($id);
        if (!$subcategory) {
            return response()->json(['message' => 'SubCategory not found'], 404);
        }
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image',
            'is_active' => 'boolean',
            'parent_id' => 'nullable|exists:categories,id'
        ]);
        $subcategory->update($validated);
        return response()->json([
            'message' => 'SubCategory updated successfully',
            'subcategory' => $subcategory
        ]);
    }
    public function destroy($id)
    {
        $subcategory = SubCategory::find($id);
        if (!$subcategory) {
            return response()->json(['message' => 'SubCategory not found'], 404);
        }
        $subcategory->delete();
        return response()->json(['message' => 'SubCategory deleted successfully']);
    }
}