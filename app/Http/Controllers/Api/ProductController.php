<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = \App\Models\Product::with('category')->get();
        return response()->json([
            'message' => 'Product index',
            'products' => $products
        ]);
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image_url' => 'nullable|url',
            'sku' => 'required|string|unique:products,sku',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'is_active' => 'boolean',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'required|exists:subcategories,id'
        ]);
        $product = Product::create($validated);
        return response()->json([
            'message' => 'Product created successfully',
            'product' => $product
        ], 201);
    }
    public function show($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }
        return response()->json($product);
    }
    public function getByCategory($category_id)
    {
        // Verify category exists
        $category = \App\Models\Category::find($category_id);
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        // Get products
        $products = \App\Models\Product::where('category_id', $category_id)
            ->get();

        return response()->json([
            'category' => $category->name,
            'products' => $products
        ]);
    }
    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|nullable|string',
            'image_url' => 'sometimes|nullable|url',
            'sku' => 'sometimes|string|unique:products,sku,' . $id,
            'price' => 'sometimes|numeric|min:0',
            'stock' => 'sometimes|integer|min:0',
            'is_active' => 'sometimes|boolean',
            'category_id' => 'sometimes|exists:categories,id'
        ]);

        $product->update($validated);
        $product->load('category');

        return response()->json([
            'message' => 'Product updated successfully',
            'product' => $product
        ]);
    }


    // Delete product
    public function destroy($id)
    {
        $product = Product::find($id);
        if (!$product) return response()->json(['message' => 'Product not found'], 404);

        $product->delete();
        return response()->json(['message' => 'Product deleted successfully']);
    }
}
