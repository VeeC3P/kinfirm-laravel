<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductWebRoutesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function home_redirects_to_products_page()
    {
        $response = $this->get('/');
        $response->assertRedirect('/products');
    }

    /** @test */
    public function it_shows_product_index_page()
    {
        Product::factory()->count(5)->create();
        $response = $this->get('/products');
        $response->assertStatus(200)
                 ->assertSee('Products');
    }

    /** @test */
    public function it_shows_product_show_page_with_related_products()
    {
        $main = Product::factory()->create(['description' => 'Main Product']);
        $related = Product::factory()->create(['description' => 'Related Product']);

        $main->tags()->attach(\App\Models\Tag::factory()->create(['name' => 'Common']));
        $related->tags()->attach(\App\Models\Tag::first());

        Stock::factory()->create(['product_id' => $main->id, 'quantity' => 7]);

        $response = $this->get(route('products.show', $main));
        $response->assertStatus(200)
                 ->assertSee('Main Product')
                 ->assertSee('Related Product');
    }
}
