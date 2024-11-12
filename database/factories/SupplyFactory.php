<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Supply>
 */
class SupplyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => fake()->numerify('SUP######'),
            'inventory_id' => fake()->numberBetween(1, 100),
            'price' => $price = fake()->randomFloat(0, 1000, 100000),
            'unit' => fake()->randomElement(['kg', 'pcs']),
            'quantity' => $quantity = fake()->numberBetween(10, 1000),
            'subtotal' => $price * $quantity,
            'supplier_id' => fake()->numberBetween(1, 10),
            'supplied_at' => fake()->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
