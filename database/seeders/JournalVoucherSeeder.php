<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AcctJournalVoucher;
use App\Models\AcctJournalVoucherItem;

class JournalVoucherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AcctJournalVoucher::factory()->has(AcctJournalVoucherItem::factory()->count(3),'items')->count(3)->create();
    }
}
