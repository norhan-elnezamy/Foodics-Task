<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\StockItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductIngredientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'product_id' => Product::factory(),
            'stock_item_id' => StockItem::factory(),
            'kg_quantity' => rand(10, 150) / 1000,
        ];
    }
}
