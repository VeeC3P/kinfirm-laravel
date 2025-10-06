<?php

namespace Tests\Feature;

use App\Models\Tag;
use Tests\TestCase;
use App\Models\User;
use App\Models\Stock;
use App\Models\Product;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductApiTest extends TestCase
{
    use RefreshDatabase;
    private $user;
    private function authenticate(): string
    {
        // Add user for other units to test
        $this->user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => Hash::make('Password1!'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'user@example.com',
            'password' => 'Password1!',
        ]);

        return $response->json('token');
    }

    /** @test */
    public function it_returns_paginated_products_with_tags()
    {
        Product::factory()->count(3)->has(Tag::factory()->count(2))->create();

        $token = $this->authenticate();

        $response = $this->getJson('/api/products', [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['data' => [['id', 'sku', 'description', 'tags']]]);
    }

    /** @test */
    public function it_returns_single_product_with_live_stock()
    {
        $product = Product::factory()->create();
        Stock::factory()->create(['product_id' => $product->id, 'quantity' => 8]);

        $token = $this->authenticate();

        $response = $this->getJson("/api/products/{$product->id}", [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'product' => ['id' => $product->id],
                'stocks' => [
                    [
                        'quantity' => 8,
                    ]
                ]
            ]);

    }
}
