<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;

class StockFactory extends Factory
{
    public function definition(): array
    {
        return [
            // stockは外部キー制約で、product_idを持ってい。
            // Product::factory()で生成した順に登録される。
            // App\Models\ProductにHasFactoryでProduct::factory()を使用できる。
            'product_id' => Product::factory(),
            'type' => $this->faker->numberBetween(1,2),
            'quantity' => $this->faker->randomNumber,
        ];
    }
}
