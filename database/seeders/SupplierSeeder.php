<?php

namespace Database\Seeders;

use App\Models\CoreSupplier;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CoreSupplier::factory()->count(20)->create();
    }
}
