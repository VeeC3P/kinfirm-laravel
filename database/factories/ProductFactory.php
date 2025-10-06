<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        $sku = strtoupper('KF-' . $this->faker->unique()->numberBetween(100, 999));

        return [
            'sku' => $sku,
            'description' => $this->faker->sentence(),
            'size' => $this->faker->randomElement(['S', 'M', 'L', 'XL', '2XL']),
            'photo' => $this->faker->imageUrl(125, 100),
            'source_updated_at' => $this->faker->dateTimeBetween('-2 years', 'now'),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
