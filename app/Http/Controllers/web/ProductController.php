<?php
namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('tags','stocks')->paginate(20);
        return view('products.index', compact('products'));
    }

    public function show(Product $product)
    {
        $cacheKey = "product:core:{$product->id}";

        // --- Cache core product info (tags, name, description, price, etc.) ---
        $productCore = Cache::remember($cacheKey, now()->addHours(6), function () use ($product) {
            $core = $product->load('tags');
            
            // Convert tags to only necessary attributes
            $core->tags = $core->tags->map(fn($tag) => $tag->only(['id','name','slug']));
            
            return $core;
        });

        // --- Real-time stocks (do not cache) ---
        $stocks = $product->stocks()->get(['city','quantity']); // collection of stocks
        // --- Related products via shared tags ---
        $tagIds = $product->tags->pluck('id'); // get current product tag IDs
        $related = Product::whereHas('tags', function ($q) use ($tagIds) {
                $q->whereIn('tags.id', $tagIds);
            })
            ->where('id', '!=', $product->id) // exclude current product
            ->with('tags')
            ->limit(4)
            ->get();
        return view('products.show', [
            'productCore'   => $productCore,
            'stocks'        => $stocks,
            'related'       => $related,
        ]);
    }

}
