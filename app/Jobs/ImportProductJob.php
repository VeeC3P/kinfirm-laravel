<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Models\Tag;

class ImportProductJob implements ShouldQueue
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

        $product = Product::updateOrCreate(
            ['sku' => $row['sku']],
            [
                'description'       => $row['description'] ?? null,
                'size'              => $row['size'] ?? null,
                'photo'             => $row['photo'] ?? null,
                'source_updated_at' => $row['updated_at'] ?? null,
            ]
        );

        // --- Tags: always objects with { title } ---
        $tagIds = [];
        if (!empty($row['tags']) && is_array($row['tags'])) {
            foreach ($row['tags'] as $tagData) {
                if (!isset($tagData['title'])) {
                    continue;
                }

                $name = trim($tagData['title']);

                $tag = Tag::firstOrCreate(
                    ['slug' => Str::slug($name)],
                    ['name' => $name]
                );

                $tagIds[] = $tag->id;
            }
        }

        $product->tags()->sync($tagIds);

        // Invalidate cached product details
        Cache::forget("product:core:{$product->id}");
    }
}
