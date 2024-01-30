<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InvtItem>
 */
class InvtItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'company_id' =>  1,
            'item_category_id' => mt_rand(0,5),
            'item_code' => fake()->word(5),
            'item_name' => fake()->word(7),
            'item_remark' => fake()->sentence(5),
            'item_unit_id' => mt_rand(0,3),
            'item_default_quantity' => fake()->randomNumber(4),
            'item_unit_price' => fake()->randomNumber(4),
            'item_unit_cost' => fake()->randomNumber(4)
        ];
    }
}
