<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::create(
            [
                'name' => 'Beef Burger',
                'description' => 'The description write here',
            ]
        )->ingredients()->createMany(
            [
                [
                    'stock_item_id' => 1,
                    'kg_quantity' => 150 / 1000
                ],
                [
                    'stock_item_id' => 2,
                    'kg_quantity' => 30 / 1000
                ],
                [
                    'stock_item_id' => 3,
                    'kg_quantity' => 20 / 1000
                ]
            ]
        );

        Product::create(
            [
                'name' => 'Checkin Burger',
                'description' => 'The description write here',
            ]
        )->ingredients()->createMany(
            [
                [
                    'stock_item_id' => 4,
                    'kg_quantity' => 150 / 1000
                ],
                [
                    'stock_item_id' => 2,
                    'kg_quantity' => 30 / 1000
                ],
                [
                    'stock_item_id' => 3,
                    'kg_quantity' => 20 / 1000
                ]
            ]
        );
    }
}
