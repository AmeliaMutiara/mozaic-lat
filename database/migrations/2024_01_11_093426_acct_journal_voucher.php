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
        if(!Schema::hasTable('acct_journal_voucher')){
            Schema::create('acct_journal_voucher', function (Blueprint $table) {
                $table->id('journal_voucher_id');
                $table->unsignedBigInteger('company_id')->nullable();
                $table->unsignedBigInteger('transaction_module_id')->nullable();
                $table->unsignedBigInteger('journal_voucher_status')->nullable()->default(0);
                $table->string('transaction_journal_no')->nullable();
                $table->string('transaction_module_code')->nullable();
                $table->string('journal_voucher_date')->nullable();
                $table->string('journal_voucher_description')->nullable();
                $table->string('journal_voucher_period')->nullable();
                $table->string('journal_voucher_no')->nullable();
                $table->string('journal_voucher_title')->nullable();
                $table->tinyInteger('reverse_state')->nullable();
                $table->string('voided_remark')->nullable();
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
