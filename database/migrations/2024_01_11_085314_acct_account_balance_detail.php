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
        if(!Schema::hasTable('acct_account_balance_detail')){
            Schema::create('acct_account_balance_detail', function (Blueprint $table) {
                $table->id('account_balance_detail_id');
                $table->unsignedBigInteger('account_id')->nullable();
                $table->unsignedBigInteger('transaction_id')->nullable();
                $table->string('transaction_type')->nullable();
                $table->string('transaction_code')->nullable();
                $table->string('transaction_date')->nullable();
                $table->string('opening_balance')->nullable();
                $table->string('account_out')->nullable();
                $table->string('last_balance')->nullable();
                $table->unsignedBigInteger('created_id')->nullable();
                $table->unsignedBigInteger('updated_id')->nullable();
                $table->timestamp('last_update')->nullable();
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
