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
        if(!Schema::hasTable('preference_company')){
            Schema::create('preference_company', function(Blueprint $table){
                $table->id('company_id');
                $table->string('company_name')->nullable();
                $table->string('company_address')->nullable();
                $table->string('company_phone_number')->nullable();
                $table->string('company_mobile_number')->nullable();
                $table->string('company_email')->nullable();
                $table->string('company_website')->nullable();
                $table->string('company_logo')->nullable();
                $table->string('account_payable_id')->nullable();
                $table->string('account_shortover_id')->nullable();
                $table->string('ppn_percentage')->nullable();
                $table->string('pinter_address')->nullable();
                $table->string('receipt_bottom_text')->nullable();
                $table->unsignedBigInteger('created_id')->nullable();
                $table->unsignedBigInteger('updated_id')->nullable();
                $table->unsignedBigInteger('deleted_id')->nullable();
                $table->timestamps();
                $table->softDeletes();
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
