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
        if(!Schema::hasTable('invt_warehouse')){
            Schema::create('invt_warehouse', function (Blueprint $table) {
                $table->id('warehouse_id');
                $table->unsignedBigInteger('company_id')->nullable();
                $table->string('warehouse_code')->nullable();
                $table->string('warehouse_name')->nullable();
                $table->string('warehouse_address')->nullable();
                $table->string('warehouse_phone')->nullable();
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
