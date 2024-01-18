<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CoreBank>
 */
class CoreBankFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'bank_code' => fake()->word(),
            'bank_name' => fake()->word(),
            'account_no'=> fake()->randomNumber(6),
            'onbehalf'  => fake()->name(),
            'bank_remark' => fake()->sentence(5),
            'account_id'=> mt_rand(1,5)
        ];
    }
}
