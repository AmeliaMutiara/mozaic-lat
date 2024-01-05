<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InvtWarehouse>
 */
class InvtWarehouseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'warehouse_code' => fake()->word(),
            'warehouse_name' => fake()->word(),
            'warehouse_address' => fake()->sentence(3),
            'warehouse_phone' => fake()->randomNumber()
        ];
    }
}
