<?php

namespace Database\Seeders;

use App\Models\InvtItemUnit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        InvtItemUnit::factory()->count(10)->create();
    }
}
