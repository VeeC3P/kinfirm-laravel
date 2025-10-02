<?php

namespace App\Jobs;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ImportStockJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected array $row;

    public function __construct(array $row)
    {
        $this->row = $row;
    }

    public function handle(): void
    {
        $row = $this->row;

        $product = Product::where('sku', $row['sku'])->first();
        if (!$product) {
            Log::error('no product found: SKU: ' . $row['sku']);
            return; // skip unknown products and prevent crashing
        }

        $product->stocks()->updateOrCreate(
            ['city' => $row['city']],
            ['quantity' => $row['stock']]
        );
    }
}
