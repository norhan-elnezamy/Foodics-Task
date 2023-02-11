<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class StockItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->sentence(2),
            'initial_kg_quantity' => 1,
            'current_kg_quantity' => 1
        ];
    }
}
