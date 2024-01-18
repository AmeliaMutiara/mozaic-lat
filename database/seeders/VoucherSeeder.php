<?php

namespace Database\Seeders;

use App\Models\PreferenceVoucher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VoucherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PreferenceVoucher::factory()->count(10)->create();
    }
}
