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
        if(!Schema::hasTable('preference_voucher')){
            Schema::create('preference_voucher', function (Blueprint $table) {
                $table->id('voucher_id');
                $table->unsignedBigInteger('company_id')->nullable();
                $table->string('voucher_code')->nullable();
                $table->string('voucher_amount')->nullable();
                $table->string('start_voucher')->nullable();
                $table->string('end_voucher')->nullable();
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
