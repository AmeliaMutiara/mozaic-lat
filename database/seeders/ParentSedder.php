<?php

namespace Database\Seeders;

use App\Models\Child;
use App\Models\ParentTable;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ParentSedder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ParentTable::factory()->count(20)->has(Child::factory()->count(20),'child')->create();
    }
}
