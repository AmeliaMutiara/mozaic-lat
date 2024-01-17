<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AcctJournalVoucher>
 */
class AcctJournalVoucherFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'transaction_module_id'             => 1,
            'transaction_module_code'           => 'JU',
            'journal_voucher_date'              => fake()->date('d-m-Y'),
            'journal_voucher_description'       => fake()->sentence(4)
        ];
    }
}
