<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Product;
use App\Models\Tag;
use App\Models\Stock;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function product_has_many_tags()
    {
        $product = Product::factory()->create();
        $tag = Tag::factory()->create();
        $product->tags()->attach($tag);

        $this->assertTrue($product->tags->contains($tag));
    }

    /** @test */
    public function product_has_many_stocks()
    {
        $product = Product::factory()->create();
        $stock = Stock::factory()->create(['product_id' => $product->id]);

        $this->assertTrue($product->stocks->contains($stock));
    }

    /** @test */
    public function total_stock_sum_returns_correct_value()
    {
        $product = Product::factory()->create();
        Stock::factory()->create(['product_id' => $product->id, 'quantity' => 5]);
        Stock::factory()->create(['product_id' => $product->id, 'quantity' => 10]);

        $this->assertEquals(15, $product->stocks->sum('quantity'));
    }
}
