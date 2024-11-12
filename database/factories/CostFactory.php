<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cost>
 */
class CostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => fake()->numerify('COST######'),
            'inventory_id' => fake()->numberBetween(1, 100),
            'quantity' => fake()->numberBetween(10, 100),
            'unit' => fake()->randomElement(['kg', 'pcs']),
            'cost_at' => fake()->dateTimeBetween('-1 year', 'now'),
            'note' => fake()->sentence(),
        ];
    }
}
