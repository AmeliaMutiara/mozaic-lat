<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InvtItemCategory>
 */
class InvtItemCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'company_id'        => 1,
            'item_category_code' => fake()->word(),
            'item_category_name' => fake()->word(),
            'item_category_remark' => fake()->sentence(3)
        ];
    }
}
