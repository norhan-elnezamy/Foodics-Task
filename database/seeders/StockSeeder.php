<?php

namespace Database\Seeders;

use App\Models\StockItem;
use Illuminate\Database\Seeder;

class StockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $stockItems = [
            [
                'id' => 1,
                'name' => 'Beef',
                'initial_kg_quantity' => 20,
                'current_kg_quantity' => 20
            ],
            [
                'id' => 2,
                'name' => 'Cheese',
                'initial_kg_quantity' => 5,
                'current_kg_quantity' => 5
            ],
            [
                'id' => 3,
                'name' => 'Onion',
                'initial_kg_quantity' => 1,
                'current_kg_quantity' => 1
            ],
            [
                'id' => 4,
                'name' => 'Chicken',
                'initial_kg_quantity' => 20,
                'current_kg_quantity' => 20
            ],
        ];
        StockItem::insert($stockItems);
    }
}
