<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AcctAccount>
 */
class AcctAccountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'company_id' => 1,
            'account_code'   => fake()->randomNumber(),
            'account_name'   => fake()->word(),
            'account_group'  => fake()->randomNumber(),
            'account_type_id'=> mt_rand(0,3),
            'account_status' => mt_rand(0,1)
        ];
    }
}
