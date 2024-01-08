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
        if(!Schema::hasTable('core_supplier')){
            Schema::create('core_supplier', function (Blueprint $table) {
                $table->id('supplier_id');
                $table->unsignedBigInteger('company_id')->nullable();
                $table->string('supplier_name')->nullable();
                $table->string('supplier_phone')->nullable(); 
                $table->string('supplier_address')->nullable();
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
