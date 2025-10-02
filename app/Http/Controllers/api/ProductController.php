<?php
namespace App\Http\Controllers\Api;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
    // GET /api/products
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 20);
        $products = Product::with('tags')->paginate($perPage);
        return response()->json($products);
    }

    // GET /api/products/{product}
    public function show(Product $product)
    {
        // Cache "core" product data (tags, description, size, photo, etc.)
        $cachedProduct = Cache::remember(
            "product:core:{$product->id}", 
            3600, // 1 hour
            fn () => $product->load('tags')
        );

        // Always fetch stock in real-time
        $stocks = $product->stocks()->get(['city', 'quantity']);

        return response()->json([
            'product' => $cachedProduct,
            'stocks'  => $stocks,
        ]);
    }
}
