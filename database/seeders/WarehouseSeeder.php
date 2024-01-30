<?php

namespace Database\Seeders;

use App\Models\InvtWarehouse;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        InvtWarehouse::factory()->count(1)->create();
    }
}
