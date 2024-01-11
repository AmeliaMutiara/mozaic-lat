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
        if(!Schema::hasTable('preference_transaction_module')){
            Schema::create('preference_transaction_module', function (Blueprint $table) {
                $table->id('transaction_module_id');
                $table->string('transaction_module_name')->nullable();
                $table->string('transaction_module_code')->nullable();
                $table->string('transaction_controller')->nullable();
                $table->string('transaction_table')->nullable();
                $table->string('transaction_primary_key')->nullable();
                $table->unsignedBigInteger('status')->nullable();
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
