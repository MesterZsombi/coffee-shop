<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductImageController extends Controller
{
    public function store(Request $request, Product $product): JsonResponse
    {
        $request->validate([
            'image' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ]);

        $path = $request->file('image')->store('products', 'public');

        if ($path === false) {
            return response()->json([
                'message' => 'Image upload failed.',
            ], 500);
        }

        $product->update([
            'display_image_url' => url('/storage/' . $path),
        ]);

        return response()->json([
            'message' => 'Product image uploaded successfully.',
            'product_id' => $product->id,
            'image_url' => $product->display_image_url,
            'path' => $path,
        ]);
    }
}
