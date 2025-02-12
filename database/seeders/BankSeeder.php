<?php

namespace Database\Seeders;

use App\Models\CoreBank;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CoreBank::factory()->count(5)->create();
    }
}
