<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PreferenceVoucher>
 */
class PreferenceVoucherFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'voucher_code' => fake()->word(4),
            'voucher_amount' => fake()->randomNumber(2),
            'start_voucher' => fake()->date(),
            'end_voucher' => fake()->date()
        ];
    }
}
