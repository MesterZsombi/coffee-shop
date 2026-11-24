<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // GET /api/products
    public function index(): JsonResponse
    {
        return response()->json(
            Product::with('category')->paginate(10)
        );
    }

    // GET /api/products/{product}
    public function show(Product $product): JsonResponse
    {
        return response()->json(
            $product->load('category')
        );
    }

    // POST /api/products
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|string|max:10',
            'display_image_url' => 'required|url',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $product = Product::create($validated);

        return response()->json($product, 201);
    }

    // PUT/PATCH /api/products/{product}
    public function update(
        Request $request,
        Product $product
    ): JsonResponse {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'price' => 'sometimes|required|numeric|min:0',
            'currency' => 'sometimes|required|string|max:10',
            'display_image_url' => 'sometimes|required|url',
            'category_id' => 'sometimes|nullable|exists:categories,id',
        ]);

        $product->update($validated);

        return response()->json($product);
    }

    // DELETE /api/products/{product}
    public function destroy(Product $product): JsonResponse
    {
        $product->delete();

        return response()->json([
            'message' => 'Product deleted successfully'
        ]);
    }
}
