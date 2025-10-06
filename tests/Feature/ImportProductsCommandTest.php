<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ImportProductsCommandTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_imports_products_and_stocks_via_queue()
    {
        // Fake HTTP responses for the JSON files
        Http::fake([
            'https://kinfirm.com/app/uploads/laravel-task/products.json' => Http::response([
                [
                    'sku' => 'KF-100',
                    'description' => 'Test product description',
                    'size' => 'M',
                    'photo' => 'http://dummyimage.com/125x100.png/cc0000/ffffff',
                    'tags' => [['title' => 'TagA'], ['title' => 'TagB']],
                    'updated_at' => '2022-01-01',
                ]
            ], 200),
            'https://kinfirm.com/app/uploads/laravel-task/stocks.json' => Http::response([
                ['sku' => 'KF-100', 'city' => 'Paris', 'stock' => 5],
                ['sku' => 'KF-100', 'city' => 'Tokyo', 'stock' => 3],
            ], 200),
        ]);

        // Run commands with --test to execute jobs immediately
        $this->artisan('import:products', ['--test' => true])->assertExitCode(0);
        $this->artisan('import:stocks', ['--test' => true])->assertExitCode(0);

        // Assertions after jobs have run
        $this->assertDatabaseHas('products', [
            'sku' => 'KF-100',
            'description' => 'Test product description',
        ]);

        $this->assertDatabaseHas('tags', ['name' => 'TagA']);
        $this->assertDatabaseHas('tags', ['name' => 'TagB']);

        $this->assertDatabaseHas('stocks', ['city' => 'Paris', 'quantity' => 5]);
        $this->assertDatabaseHas('stocks', ['city' => 'Tokyo', 'quantity' => 3]);
    }
}
