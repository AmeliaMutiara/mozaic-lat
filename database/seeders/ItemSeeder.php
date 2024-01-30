<?php

namespace Database\Seeders;

use App\Models\InvtItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        InvtItem::factory()->count(4)->create();
    }
}
