<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AcctJournalVoucherItem>
 */
class AcctJournalVoucherItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'account_id'                        => mt_rand(1,9),
            'account_id_status'                 => mt_rand(0,1),
            'journal_voucher_amount'            => fake()->randomNumber(2),
            'journal_voucher_id'                => mt_rand(0,1)
        ];
    }
}
