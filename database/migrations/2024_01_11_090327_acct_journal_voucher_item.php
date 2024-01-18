<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if(!Schema::hasTable('acct_journal_voucher_item')){
            Schema::create('acct_journal_voucher_item', function (Blueprint $table) {
                $table->id('journal_voucher_item_id');
                $table->unsignedBigInteger('company_id')->nullable();
                $table->unsignedBigInteger('journal_voucher_id')->nullable();
                $table->unsignedBigInteger('account_id')->nullable();
                $table->string('journal_voucher_amount')->nullable();
                $table->unsignedBigInteger('account_id_status')->nullable();
                $table->unsignedBigInteger('account_id_default_status')->nullable();
                $table->string('journal_voucher_debit_amount')->nullable();
                $table->string('journal_voucher_credit_amount')->nullable();
                $table->tinyInteger('reverse_state')->nullable()->default(0);
                $table->unsignedBigInteger('created_id')->nullable();
                $table->unsignedBigInteger('updated_id')->nullable();
                $table->unsignedBigInteger('deleted_id')->nullable();
                $table->timestamps();
                $table->softDeletesTz();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
