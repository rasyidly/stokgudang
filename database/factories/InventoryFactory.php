<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Inventory>
 */
class InventoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->words(2, true),
            'brand' => fake()->company(),
            'quantity' => fake()->numberBetween(1, 1000),
            'unit' => fake()->randomElement(['kg', 'pcs']),
            'supplier_id' => fake()->numberBetween(1, 10),
        ];
    }
}
